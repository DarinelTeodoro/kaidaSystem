let table = $('#table-tickets').DataTable({
    ajax: 'tickets-list.php',
    columns: [
        { data: 'id' },
        { data: 'cliente' },
        { data: 'estado' },
        { data: 'button' }
    ]
});