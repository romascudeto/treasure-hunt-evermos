<?php

// Define all treasure's spot 
$treasureArr = array(
    [1, 1], [1, 2], [1, 3], [1, 4], [1, 5], [1, 6],
    [2, 1], [2, 5], [2, 6],
    [3, 1], [3, 2], [3, 3], [3, 5],
    [4, 3], [4, 4], [4, 5], [4, 6]
);

// Define starting point
$startPosition = [4, 1];

// Define a clear path and an obstacle
$arr = array(
    array("#", "#", "#", "#", "#", "#", "#", "#"),
    array("#", ".", ".", ".", ".", ".", ".", "#"),
    array("#", ".", "#", "#", "#", ".", ".", "#"),
    array("#", ".", ".", ".", "#", ".", "#", "#"),
    array("#", ".", "#", ".", ".", ".", ".", "#"),
    array("#", "#", "#", "#", "#", "#", "#", "#"),
);

// Randoming treasure's spot
$treasure = $treasureArr[rand(0, 15)];

// Symbolize treasure's spot with O
$arr[$treasure[0]][$treasure[1]] = "O";
// Symbolize starting point with X
$arr[$startPosition[0]][$startPosition[1]] = "X";
$navigationArr = [];

// Drawing map before find treasure
printPath($arr);

// Logic to find treasure
findPath($arr, $startPosition, $treasure);
echo "\n";
// Drawing map after find treasure
printPath($arr);
groupingPath();

function printPath($arr)
{
    $row = 6;
    $col = 8;
    for ($i = 0; $i < $row; $i++) {
        for ($j = 0; $j < $col; $j++) {
            echo $arr[$i][$j] . " ";
        }
        echo "\n";
    }
    echo "\n";
}

function findPath(&$arr, $startPosition, $treasure)
{
    $found = false;
    $lastPosition = [$startPosition[0], $startPosition[1]];
    $firstStep = true;
    while (!$found) {
        if (checkTreasure($arr, $lastPosition)) {
            $found = true;
            break;
        }
        if ($firstStep) { // Check if it is the first step
            $lastPosition = goUp($lastPosition); // First step must be up
            markPath($arr, $lastPosition);
            $firstStep = false;
            continue;
        }

        // Check if the treasure is located above the first step
        // If the treasure is located above the first step then go to up path 
        if ($treasure[0] < $startPosition[0] - 1) {
            if (checkUp($arr, $lastPosition) != "#") { // Check Path Up
                $lastPosition = goUp($lastPosition);
            } else {
                if (checkRight($arr, $lastPosition) != "#") { // Check Path Right
                    $lastPosition = goRight($lastPosition);
                } else {
                    if (checkDown($arr, $lastPosition) != "#") { // Do Down
                        $lastPosition = goDown($lastPosition);
                    }
                }
            }
        }

        // If the treasure is located below the first step then go to right path 
        else {
            if (checkRight($arr, $lastPosition) != "#") { // Do Right
                $lastPosition = goRight($lastPosition);
            } else {
                if (checkDown($arr, $lastPosition) != "#") { // Do Down
                    $lastPosition = goDown($lastPosition);
                }
            }
        }
        markPath($arr, $lastPosition);
    }
}

function checkUp($arr, $lastPosition)
{
    // If value not found or not set then return wall
    if (isset($arr[$lastPosition[0] - 1][$lastPosition[1]])) {
        return $arr[$lastPosition[0] - 1][$lastPosition[1]];
    } else {
        return "#";
    }
}

function checkRight($arr, $lastPosition)
{
    // If value not found or not set then return wall
    if (isset($arr[$lastPosition[0]][$lastPosition[1] + 1])) {
        return $arr[$lastPosition[0]][$lastPosition[1] + 1];
    } else {
        return "#";
    }
}

function checkDown($arr, $lastPosition)
{
    // If value not found or not set then return wall
    if (isset($arr[$lastPosition[0] + 1][$lastPosition[1]])) {
        return $arr[$lastPosition[0] + 1][$lastPosition[1]];
    } else {
        return "#";
    }
}

function checkTreasure(&$arr, $lastPosition)
{
    if (checkUp($arr, $lastPosition) == "O") {
        $lastPosition = goUp($lastPosition);
    } else if (checkRight($arr, $lastPosition) == "O") {
        $lastPosition = goRight($lastPosition);
    } else if (checkDown($arr, $lastPosition) == "O") {
        $lastPosition = goDown($lastPosition);
    } else {
        return false;
    }
    $arr[$lastPosition[0]][$lastPosition[1]] = "$";
    return true;
}

function goUp($lastPosition)
{
    global $navigationArr;
    array_push($navigationArr, "Up");
    return [$lastPosition[0] - 1, $lastPosition[1]];
}

function goRight($lastPosition)
{
    global $navigationArr;
    array_push($navigationArr, "Right");
    return [$lastPosition[0], $lastPosition[1] + 1];
}

function goDown($lastPosition)
{
    global $navigationArr;
    array_push($navigationArr, "Down");
    return [$lastPosition[0] + 1, $lastPosition[1]];
}

function groupingPath()
{
    global $navigationArr;
    $result = [];
    $carry = [array_shift($navigationArr) => 1];
     
    foreach ($navigationArr as $value) {
        if (isset($carry[$value])) {
            ++$carry[$value];
        } else {
            $result[] = $carry;
            $carry = [$value => 1];
        }
    }
    $result[] = $carry;
    foreach($result as $value) {
        foreach($value as $key => $val) {
            if ($val > 1){
                echo $key ." ". $val . " step(s) \n";
            }else{
                echo $key ." ". $val . " step \n";
            }
        }
    }

}

function markPath(&$arr, $lastPosition)
{
    $arr[$lastPosition[0]][$lastPosition[1]] = "$";
}

