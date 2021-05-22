<?php 

namespace App\Model\Router;

class RouteParameters
{
    public function getParameters(string $uri, array $routes): false | array
    {
        $arguments = array();

        $matchingRouteRawUri = $this->getRawUriAndMatchingRoute($uri, $routes)['raw_uri'];

        $argumentsPositions = $this->getArgumentsPositions($matchingRouteRawUri);

        foreach ($argumentsPositions as $key => [$startPosition, $endPosition])
        {
            $argument = "";

            $routeLetters = str_split($uri);
            
            if ($key == 0)
            {
                foreach ($routeLetters as $letterKey => $letter)
                {
                    $didArgumentBegin = $letterKey >= $startPosition;

                    if ($didArgumentBegin)
                    {
                        if ($letter == "/") break;

                        $argument .= $letter;
                    }
                }

                $arguments[] = $argument;
                continue;
            }

            $previousArgumentLength = strlen($arguments[$key - 1]);
            $previousArgumentStartingPosition = $argumentsPositions[$key - 1][0];
            $previousArgumentEndPosition = $argumentsPositions[$key - 1][1];

            /* minus one because it counts last character of previous argument */
            $lengthFromPreviousArgumentEnd = ($startPosition - $previousArgumentEndPosition) - 1;

            /* In uri with actual data, not raw uri */
            $actualArgumentStartingPosition = ($previousArgumentStartingPosition + $previousArgumentLength) + $lengthFromPreviousArgumentEnd;

            $isThisArgumentEndOfTheUri = strlen($matchingRouteRawUri) - 1 == $endPosition;
            
            foreach ($routeLetters as $letterKey => $letter)
            {
                $didArgumentBegin = $letterKey >= $actualArgumentStartingPosition;

                if (!$isThisArgumentEndOfTheUri && $didArgumentBegin && $letter == "/") break;

                if ($didArgumentBegin) $argument .= $letter;
            }

            $arguments[] = $argument;
        }

        return $arguments;
    }

    public function getRawUriAndMatchingRoute(string $uri, array $routes): array | false
    {
        foreach ($routes as $rawUri => $route)
        {
            $pattern = $this->buildPattern($rawUri);

            preg_match($this->buildPattern($rawUri), $uri, $matches);

            /* Make sure it is complete match, preg_match returns 1 even in this case */
            /* \/[[:ascii:]]{0,}\/simulate\/race - /10/simulate/race/test, there is additional string "test" but what was before matched the pattern */
            if (isset($matches[0]) && $matches[0] == $uri)
            {
                return ['raw_uri' => $rawUri, 'route' => $route];
            }
        }

        return false;
    }

    public function getArgumentsPositions(string $rawUri): array
    {
        /* [['begining', 'end'], ['beginning', 'end']] */
        /* Raw uri "/photo/{id}" */
        /* For instance if begining of the argument in raw uri is 8 letter and ending is 11 letter than [7, 10] since we start from zero */
        $argumentsPositions = array();
        $argumentCount = 0;

        $routeLetters = str_split($rawUri);

        foreach ($routeLetters as $letterPosition => $letter)
        {
            switch ($letter) {
                case '/':
                    $nextLetterPosition = $letterPosition + 1;
                    if (substr($rawUri, $nextLetterPosition, 1) == '{') $argumentsPositions[$argumentCount][0] = $nextLetterPosition;
                    break;
                case '}': 
                    $argumentsPositions[$argumentCount][1] = $letterPosition;
                    $argumentCount++;
                    break;
            }
        }

        return $argumentsPositions;
    }

    public function buildPattern($rawUri)
    {
        /* Basicly if you have string like /photo/update pattern will be the same /photo/update */
        /* But if there will be argument like /photo/update/{id} than in place of argument put [[:ascii:]]{0,} so it will be /photo/update/[[:ascii:]]{0,} */
        /* If there will be more arguments do it analogically /{race_name}/{race_id}/{race_date} will become  /[[:ascii:]]{0,}/[[:ascii:]]{0,}/[[:ascii:]]{0,} */
        $pattern = '';
        $foundParameters = array();

        foreach (str_split($rawUri) as $letter)
        {
            switch ($letter)
            {
                case '{': 
                    $pattern .= '[[:ascii:]]{0,}';
                    $foundParameters[][0] = '{'; 
                    break;
                case '}':
                    $foundParameters[count($foundParameters) - 1][1] = '}';
                    break;
                case '/':
                    $pattern .= '\\' . $letter;
                    break;
                default: 
                    $index = count($foundParameters) - 1;

                    if (isset($foundParameters[$index]) && count($foundParameters[$index]) == 2)
                    {
                        $pattern .= $letter;
                    }
                    else if (!isset($foundParameters[$index]))
                    {
                        $pattern .= $letter;
                    }
            }
        }

        return '/' . $pattern . '/';
    }
}