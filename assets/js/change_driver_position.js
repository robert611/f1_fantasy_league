let increaseDriverPositionTriggers = document.getElementsByClassName('increase-driver-position');
let decreaseDriverPositionTriggers = document.getElementsByClassName('decrease-driver-position');

function increaseDriverPosition(event)
{
    let upTableRow = event.srcElement.parentElement.parentElement;
    let downTableRow = getPreviousSibling(upTableRow);

    changeTablesData(upTableRow, downTableRow);
}

function decreaseDriverPosition(event)
{
    let downTableRow = event.srcElement.parentElement.parentElement;
    let upTableRow = getNextSibling(downTableRow);

    changeTablesData(upTableRow, downTableRow);
}

function changeTablesData(upTableRow, downTableRow)
{
    let upDriverIcons = getTableRowIcons(upTableRow);

    [upDriverId, upCurrentDriverPosition] = getDriverDataFromIcon(upDriverIcons[0]);

    let downDriverIcons = getTableRowIcons(downTableRow);

    [downDriverId, downCurrentDriverPosition] = getDriverDataFromIcon(downDriverIcons[0]);

    let upDriverPositionTableData = document.getElementById(`driver-position-${upDriverId}`);
    let downDriverPositionTableData = document.getElementById(`driver-position-${downDriverId}`);

    let newUpDriverPosition = parseInt(upCurrentDriverPosition) - 1;
    let newDownDriverPosition = parseInt(downCurrentDriverPosition) + 1;

    let driverWithIncreasedPositionInput = document.getElementById(`driver-position-input-${upDriverId}`);
    let driverWithDecreasedPositionInput = document.getElementById(`driver-position-input-${downDriverId}`);

    setNewDriverPositionToIcons(upDriverIcons, newUpDriverPosition);
    setNewDriverPositionToIcons(downDriverIcons, newDownDriverPosition);

    upDriverPositionTableData.textContent = newUpDriverPosition;
    downDriverPositionTableData.textContent = newDownDriverPosition;

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

    return [driverId, currentDriverPosition];
}

function setNewDriverPositionToIcons(icons, position)
{
    for (iconIndex in icons)
    {
        let icon = icons[iconIndex];

        icon.setAttribute('data-driverposition', position);
    }
}

function setNewDriverInputName(input, position, driverId)
{
    let name = `driver_position[${driverId}][${position}]`;

    input.setAttribute('name', name);
}

Array.from(increaseDriverPositionTriggers).forEach((trigger) => {
    trigger.addEventListener('click', (event) => { increaseDriverPosition(event) });
});

Array.from(decreaseDriverPositionTriggers).forEach((trigger) => {
    trigger.addEventListener('click', (event) => { decreaseDriverPosition(event) });
});