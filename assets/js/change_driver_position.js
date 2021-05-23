let increaseDriverPositionTriggers = document.getElementsByClassName('increase-driver-position');
let decreaseDriverPositionTriggers = document.getElementsByClassName('decrease-driver-position');

function increaseDriverPosition(event)
{
    let upTableRow = event.srcElement.parentElement.parentElement;
    let downTableRow = getPreviousSibling(upTableRow);
    
    if (downTableRow.tagName !== "TR" || downTableRow == undefined) return;

    changeTablesData(upTableRow, downTableRow);
}

function decreaseDriverPosition(event)
{
    let downTableRow = event.srcElement.parentElement.parentElement;
    let upTableRow = getNextSibling(downTableRow);

    if (upTableRow == undefined || upTableRow.tagName !== "TR") return;

    changeTablesData(upTableRow, downTableRow);
}

function changeTablesData(upTableRow, downTableRow)
{
    let upDriverIcons = getTableRowIcons(upTableRow);

    [upDriverId, upCurrentDriverPosition, upCurrentDriverPoints] = getDriverDataFromIcon(upDriverIcons[0]);

    let downDriverIcons = getTableRowIcons(downTableRow);

    [downDriverId, downCurrentDriverPosition, downCurrentDriverPoints] = getDriverDataFromIcon(downDriverIcons[0]);

    let upDriverPositionTableData = document.getElementById(`driver-position-${upDriverId}`);
    let downDriverPositionTableData = document.getElementById(`driver-position-${downDriverId}`);

    let upDriverPointsTableData = document.getElementById(`driver-points-${upDriverId}`);
    let downDriverPointsTableData = document.getElementById(`driver-points-${downDriverId}`);

    let newUpDriverPosition = parseInt(upCurrentDriverPosition) - 1;
    let newDownDriverPosition = parseInt(downCurrentDriverPosition) + 1;

    let newUpDriverPoints = downCurrentDriverPoints;
    let newDownDriverPoints = upCurrentDriverPoints;

    let driverWithIncreasedPositionInput = document.getElementById(`driver-position-input-${upDriverId}`);
    let driverWithDecreasedPositionInput = document.getElementById(`driver-position-input-${downDriverId}`);

    setNewDriverPositionToIcons(upDriverIcons, newUpDriverPosition);
    setNewDriverPositionToIcons(downDriverIcons, newDownDriverPosition);

    setNewDriverPointsToIcons(upDriverIcons, newUpDriverPoints);
    setNewDriverPointsToIcons(downDriverIcons, newDownDriverPoints);

    upDriverPositionTableData.textContent = newUpDriverPosition;
    downDriverPositionTableData.textContent = newDownDriverPosition;

    upDriverPointsTableData.textContent = newUpDriverPoints;
    downDriverPointsTableData.textContent = newDownDriverPoints;

    resetDriversBackgroundColors();

    setDriverBackgroundColor(upDriverIcons[0], 'linear-green');
    setDriverBackgroundColor(downDriverIcons[1], 'linear-red');

    setNewDriverInputName(driverWithIncreasedPositionInput, newUpDriverPosition, upDriverId);
    setNewDriverInputName(driverWithDecreasedPositionInput, newDownDriverPosition, downDriverId);

    swapTableRows(upTableRow, downTableRow);
}

function swapTableRows(upRow, downRow)
{
    downRow.parentNode.insertBefore(upRow, downRow);
}

function getPreviousSibling(element)
{
    let previous = element;
    do {
        previous = previous.previousSibling;
    }
    while (previous && previous.nodeType != 1);

    return previous;
}

function getNextSibling(element)
{
    let nextSibling = element;

    do {
        nextSibling = nextSibling.nextSibling;
    } while (nextSibling !== null && nextSibling.nodeType != 1);

    return nextSibling;
}

function getTableRowIcons(tableRow)
{
    let tableRowIcons = [];

    tableRowChild = tableRow.children[0];

    do {
        for (nodeIndex in tableRowChild.children)
        {
            let node = tableRowChild.children[nodeIndex];

            if (node.tagName == "I") 
            {
              
                tableRowIcons.push(node);
                break;
            }  
        }

        tableRowChild = getNextSibling(tableRowChild);
    }
    while (tableRowChild !== null);

    return tableRowIcons;
}

function getDriverDataFromIcon(icon)
{
    let driverId = parseInt(icon.getAttribute('data-driverid'));
    let currentDriverPosition = parseInt(icon.getAttribute('data-driverposition'));
    let currentDriverPoints = parseInt(icon.getAttribute('data-driverpoints'));

    return [driverId, currentDriverPosition, currentDriverPoints];
}

function setNewDriverPositionToIcons(icons, position)
{
    for (iconIndex in icons)
    {
        let icon = icons[iconIndex];

        icon.setAttribute('data-driverposition', position);
    }
}

function setNewDriverPointsToIcons(icons, points)
{
    for (iconIndex in icons)
    {
        let icon = icons[iconIndex];

        icon.setAttribute('data-driverpoints', points);
    }
}

function setNewDriverInputName(input, position, driverId)
{
    let name = `driver_position[${driverId}][${position}]`;

    input.setAttribute('name', name);
}

function resetDriversBackgroundColors()
{
    Array.from(document.getElementsByClassName('driver-with-active-position-change-color')).forEach((tableRow) => {
        tableRow.className = "";
    });
}

function setDriverBackgroundColor(icon, colorClass)
{
    icon.parentElement.parentElement.className = `${colorClass} driver-with-active-position-change-color`;
}

Array.from(increaseDriverPositionTriggers).forEach((trigger) => {
    trigger.addEventListener('click', (event) => { increaseDriverPosition(event) });
});

Array.from(decreaseDriverPositionTriggers).forEach((trigger) => {
    trigger.addEventListener('click', (event) => { decreaseDriverPosition(event) });
});