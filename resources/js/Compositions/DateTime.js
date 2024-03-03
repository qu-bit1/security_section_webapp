import {DateTime} from "luxon";


export default function formatDate(value, humanize = false) {
    const dateTime = DateTime.fromISO(value);
    if (humanize){
        return dateTime.toRelative();
    }

    return dateTime.toFormat('LLLL dd yyyy\nT');
}
