// This file contains utility functions that are used throughout the application.
import { formatDistanceToNow, parseISO, format } from "date-fns";
// Function to format the date in a human-readable format.
export function formatDate(date) {
    if (date === null) {
      return "N/A";
    }

    date = date.replace(' ', 'T') + 'Z';
  
    const parsedDate = parseISO(date);

    return format(parsedDate, 'Pp');
  }

// Function to calculate the number of minutes, hours, days, weeks, months, or years between two dates.
export function lastUpdate(date) {
    if (!date) {
        return "N/A";
    }
    const parsedDate = parseISO(date);
    let formattedDate = formatDistanceToNow(parsedDate);
    formattedDate = formattedDate.replace("about ", "");

    return formattedDate;
}