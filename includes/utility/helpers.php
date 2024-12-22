<?php
/**
 * Returns the current date.
 *
 * @return string 'YYYY-MM-DD'
 */
function getCurrentDate(): string
{
    return date("Y-m-d");
}

/**
 * Returns the due date for a rental which is 10 days after the current date.
 *
 * @return string 'YYYY-MM-DD'
 */
function getDueDate(): string
{
    return date("Y-m-d", strtotime("+10 days"));
}