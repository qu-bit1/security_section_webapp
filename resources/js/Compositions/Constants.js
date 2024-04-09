const statusOptions = [
    {value: 'normal', label: 'Normal'},
    {value: 'open', label: 'Open'},
    {value: 'in_progress', label: 'In Progress'},
    {value: 'resolved', label: 'Resolved'},
    {value: 'closed', label: 'Closed'},
];

const shiftOptions = [
    {value: 'morning', label: 'Morning'},
    {value: 'afternoon', label: 'Afternoon'},
    {value: 'night', label: 'Night'},
];

const perPageOptions = [10, 25, 50, 100,200];


export { statusOptions, shiftOptions, perPageOptions }
