import {DateTime} from "luxon";


export function formatDate(value, humanize = false) {
    const dateTime = DateTime.fromISO(value);
    if (humanize){
        return dateTime.toRelative();
    }

    return dateTime.toFormat('LLLL dd yyyy\nT');
}

export function truncate(value = null, length = 50) {
    if (!value) return value;

    const words = value.split(' ');
    if (words.length <= length) {
        return value;
    }

    return words.slice(0, length).join(' ') + ' ...';
}

export default {
    formatDate,
    truncate
}
