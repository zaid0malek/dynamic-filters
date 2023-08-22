<!DOCTYPE html>
<html>
<head>
    <title>Dynamic Filters</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" 
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" 
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
</head>
<body>
<div class="container">
    <div class="row mt-2">
        <div class="col-12 d-flex flex-start">
            <button class="btn btn-info" data-toggle="modal" data-target="#filterModal">Filters</button>
            <span class="selected-filters ml-2"></span>
            <button class="btn btn-info" id="ApplyFilterBtn" style="display:none">Apply</button>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12 table-responsive">
            <table class="table table-bordered committee_datatable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Domain</th>
                        <th>Location</th>
                        <th>Value</th>
                        <th>Transaction Count</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel"
        aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">Select Filters</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body d-flex flex-column justify-content-center">
                <!-- Add your filter options here -->

                <div class="d-flex justify-content-between">
                <select name="filter_column" id="filter_column">
                    <option disabled selected class="default_col">Select Column</option>
                    <option value="date">Date</option>
                    <option value="domain">Domain</option>
                    <option value="location">Location</option>
                    <option value="value">Value</option>
                    <option value="transaction_count">Transaction Count</option>
                </select>
                <select name="filter_type" id="filter_type">
                    <option disabled selected>Select Filter</option>
                </select>
                </div>

                <div class="mt-3">
                    <input type="text" name="search" id="search" disabled>
                </div>

                <!-- Add more filter options as needed -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="AddFiltersBtn">Add Filter</button>
            </div>
        </div>
    </div>
</div>
</body>
<script type="text/javascript">

var table; 
$(function () {
    var allfilters;
    var selectedFilters = {};
    
    table= LoadDataTable();
    // Function to add the selected filter to the display and update the selectedFilters object
    function addSelectedFilter() {
        var column = $('#filter_column').val();
        var filterType = $('#filter_type').val();
        var filterValue = $('#search').val();

        const filterTypeDisplay = {
            'start': 'Starts With',
            'contains': 'Contains',
            'ends': 'Ends With',
            'is': 'Is',
            'between': 'Between',
            'gt': 'Greater Than',
            'lt': 'Less Than',
        };

        const columnDisplay = {
            'date': 'Date',
            'domain': 'Domain',
            'location': 'Location',
            'value': 'Value',
            'transaction_count': 'Transaction Count',
        };
        
        var newfilter = filterTypeDisplay[filterType]
        var newcolumn = columnDisplay[column]
        // If a filter value is selected, add it to the display
        if (filterValue !== "") {
            var filterText = newcolumn + ' : ' + newfilter + ' : ' + filterValue;
            var filterTag = $('<span class="badge badge-primary mr-2 filters"></span>').text(filterText);
            var deleteIcon = $('<i class="fas fa-times-circle ml-1"></i>');

            filterTag.append(deleteIcon);
        
            // Add a click event to remove the filter tag when clicked
            filterTag.on('click', function() {
                $(this).remove();
                // Remove the selected filter from the selectedFilters object
                delete selectedFilters[column];
            });
            // Add the filter to the display
            $('.selected-filters').append(filterTag);

            // Store the selected filter in the selectedFilters object
            selectedFilters[column] = { type: filterType, value: filterValue };
        }
    }

    // When the "Add Filter" button is clicked
    $('#AddFiltersBtn').on('click', function () {
        // Add the selected filter to the display
        addSelectedFilter();

        // Close the modal
        $('#filterModal').modal('hide');
        $('#ApplyFilterBtn').show();
        // Clear the filter options
        // $('#filter_column').val('').change();
        // $('#filter_type').val('').change();
        $("#filter_column option:first-child").prop("selected", true);
        $("#filter_type option:first-child").prop("selected", true);
        $('#search').val('').prop('disabled', true);
    });

    // When the "Apply Filters" button is clicked 
    $('#ApplyFilterBtn').on('click', function () {
        
        allfilters = getAllFilters();
        var allfiltersJSON = JSON.stringify(allfilters);
        // $('.committee_datatable').DataTable().destroy();

        if(table){
            table.destroy();
        }

        table = LoadDataTable(allfiltersJSON);
    })
    //add filter options on change
    $('#filter_column').on('change', function () {
        var column = $(this).val();
        allfilters = getAllFilters();
        switch (column) {
            case "domain":
            case "location":
                $('#filter_type').html('<option value="is">Is</option><option value="start">Starts With</option><option value="contains">Contains</option><option value="ends">Ends With</option>');
                $('#search').prop("type","text");
                break;
            case "value":
            case "transaction_count":
                $('#filter_type').html('<option value="is">Equals</option><option value="gt">Greater Than</option><option value="lt">Less Than</option>');        
                $('#search').prop("type","number");
                break;
            case "date":
                $('#filter_type').html('<option value="is">Is</option><option value="Between">Between</option>');
                $('#search').prop("type","date");
                break;
            default:
                break;
        }
        $('#search').prop("disabled",false);
    });

});
function getAllFilters() {
    let allfilters = $('.filters').map(function () {
            return $(this).text();
        }).get();
    return allfilters;
}
function LoadDataTable(allfiltersJSON = {}) {
    table = $('.committee_datatable').DataTable({
        processing: true,
        serverSide: true,
        dom: 'ltip',  
        ajax: {
            url: " {{route('bankfilter') }}",
            data: {
                allfilters: allfiltersJSON 
            }
        },
        columns: [
            {data: 'Date', name: 'Date'},
            {data: 'Domain', name: 'Domain'},
            {data: 'Location', name: 'Location'},
            {data: 'Value', name: 'Value'},
            {data: 'Transaction_count', name: 'Transaction Count'}
        ],
        order: [[0, 'asc']]
    });
    return table; 
}
</script>
</html>