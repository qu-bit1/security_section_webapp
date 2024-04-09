const statusOptions = [
    {value: 'normal', label: 'Normal'},
    {value: 'open', label: 'Open'},
    {value: 'in_progress', label: 'In Progress'},
    {value: 'resolved', label: 'Resolved'},
    {value: 'closed', label: 'Closed'},
];

const shiftOptions = [
    {value: '0-8', label: '00:00 - 08:00'},
    {value: '8-12', label: '8:00 - 12:00'},
    {value: '12-16', label: '12:00 - 16:00'},
    {value: '16-20', label: '16:00 - 20:00'},
    {value: '20-24', label: '20:00 - 00:00'},
];

const perPageOptions = [10, 25, 50, 100,200];


export { statusOptions, shiftOptions, perPageOptions }
