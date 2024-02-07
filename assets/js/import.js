function processCSV() {
    debugger;
    var fileInput = document.getElementById('csv_file');
    var file = fileInput.files[0];

    if (file) {
        var reader = new FileReader();

        reader.onload = function(e) {
            var csvData = e.target.result;
            var rows = csvData.split('\n');
            var processedRows = 0;

            // Array to store deferred objects for each AJAX request
            var ajaxRequests = [];

            // Process each row
            rows.forEach(function(row) {
                document.querySelector("#process").disabled = true;
                // Do something with the row data
                console.log(row);
                let rowData = row.replace(/\r/g, '').split(",")

                // Create a deferred object for the AJAX request
                var deferred = $.Deferred();


                $.ajax({
                    type: 'POST',
                    url: 'importAction',
                    data: {
                        vote_no: rowData[0],
                        cnic_no: rowData[1],
                        list_code: rowData[2],
                        polling_name: rowData[3]
                    },
                    success: function(response) {
                        console.log(response);
						processedRows++;
                        // Resolve the deferred object when the AJAX request is complete
                        deferred.resolve();
                    },
                    error: function() {
                        // Resolve the deferred object even in case of an error
                        deferred.resolve();
                    }
                });

                // Add the deferred object to the array
                ajaxRequests.push(deferred);
            });

            // Use $.when to execute code when all deferred objects are resolved
            $.when.apply($, ajaxRequests).done(function() {
                alert(processedRows + ' records imported successfully');
                document.querySelector("#process").disabled = false;
                // todo reload window now
                // window.location.reload();
            });
        };

        reader.readAsText(file);
    } else {
        alert('Please choose a CSV file.');
    }
}
