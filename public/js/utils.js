var utils = {
    ajax: function (url, data = {}, type = 'POST', successHundler = function (response) {
    }, errorHundler = function (response) {
    }) {
        let formData = new FormData();
        for (key in data) {
            formData.append(key, data[key]);
        }
        $.ajax({
            // Your server script to process the upload
            url: url,
            type: type, // POST, GET
            // Form data
            data: formData,
            // Tell jQuery not to process data or worry about content-type
            // You *must* include these options!
            cache: false,
            //headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.success) {
                    successHundler(response);
                } else {
                    errorHundler(response);
                }
            },
        });
    },

    urlparam: function (name) {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        return urlParams.get(name) || 0;
    },

}


