$(function () {

    drawAll();

    $("#add-task-btn").on("click", function (e) {
        e.preventDefault();
        var formData = new FormData(document.forms.addtaskform);
        $.ajax({
            // Your server script to process the upload
            url: '/api/create-task',
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            // Form data
            data: formData,
            // Tell jQuery not to process data or worry about content-type
            // You *must* include these options!
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.success) {
                    //$("#tasks").append(getTemplate(response.data.title, response.data.description));
                    //reload(response.data);
                    console.log("ok");
                    console.log(response);
                    $("#tasks").append(getTemplate(response.data.id, response.data.title, response.data.description, response.data.filename));
                } else {
                    console.log("error")
                }
            },
        });
    });

    $("#update-task").on("click", function (e) {
        e.preventDefault();
        var formData = new FormData();
        formData.append("title", $("#inputTitle2").val());
        formData.append("description", $("#inputDesc2").val());
        formData.append("task_id", $("#current-task-id").val());
        $.ajax({
            // Your server script to process the upload
            url: '/api/update-task',
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            // Form data
            data: formData,
            // Tell jQuery not to process data or worry about content-type
            // You *must* include these options!
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.success) {
                    //$("#tasks").append(getTemplate(response.data.title, response.data.description));
                    //reload(response.data);
                    console.log("ok");
                    drawAll();
                    $("#current-task-id").val(0);
                    $('#mymodal').modal('hide');
                } else {
                    console.log("error")
                }
            },
        });
    });

});


function drawAll() {
    utils.ajax("/api/tasks?tag=" + utils.urlparam("tag"), {}, "GET",
        function (response) {
            reload(response.data);
        },
        function (response) {
            console.log("errorHundler");
        },
    );
}

function reload(data) {
    $("#tasks").html("");
    for (let i = 0; i < data.length; i++) {
        let row = data[i];
        $("#tasks").append(getTemplate(row.id, row.title, row.description, row.filename));
    }
}

function getTemplate(id, title, desc, filename) {
    $('.template .task-title').text(title);
    $('.template .task-desc').text(desc);
    $('.template .task-img').attr('href', "images/" + filename);
    $('.template .task-thumb').attr('src', "images/th_" + filename);

    var tpl = $('.template').clone();

    tpl.attr('id', 'task-' + id);
    $(tpl).removeClass('template');
    $(tpl).removeClass('invisible');
    return tpl;
}

function deleteTask(el) {
    let id = getClosestTaskId(el);
    $.ajax({
        // Your server script to process the upload
        url: '/api/task/' + id,
        type: 'DELETE',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        // Form data
        data: {},
        // Tell jQuery not to process data or worry about content-type
        // You *must* include these options!
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            if (response.success) {
                console.log("ok");
                drawAll();
            } else {
                console.log("error")
            }
        },
    });
}

function deleteTaskImg(el) {
    let id = getClosestTaskId(el);
    $.ajax({
        // Your server script to process the upload
        url: '/api/taskimg/' + id,
        type: 'DELETE',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        // Form data
        data: {},
        // Tell jQuery not to process data or worry about content-type
        // You *must* include these options!
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            if (response.success) {
                console.log("ok");
                drawAll();
            } else {
                console.log("error")
            }
        },
    });
}

function showModalUpdateTask(el) {
    let currentTaskId = getClosestTaskId(el);
    $("#current-task-id").val(currentTaskId);

    $.ajax({
        // Your server script to process the upload
        url: '/api/task/' + currentTaskId,
        type: 'GET',
        //headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        // Form data
        data: {},
        // Tell jQuery not to process data or worry about content-type
        // You *must* include these options!
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            if (response.success) {
                $("#inputTitle2").val(response.data.title);
                $("#inputDesc2").val(response.data.description);
                //let myModal = new bootstrap.Modal(document.getElementById('mymodal'));
                //myModal.show();
                $('#mymodal').modal('show');
            } else {
                console.log("error")
            }
        },
    });
}

function ajaxLoadFile(el) {
    let id = getClosestTaskId(el);
    let form = $("#task-" + id + " form")[0];
    let formData = new FormData(form);
    formData.append('task_id', id);
    formData.append('filename', 'jfile');
    $.ajax({
        // Your server script to process the upload
        url: '/api/update-taskimg/' + id,
        type: 'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        // Form data
        data: formData,
        // Tell jQuery not to process data or worry about content-type
        // You *must* include these options!
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            if (response.success) {
                console.log("ok");
                drawAll();
            } else {
                console.log("error")
            }
        },
    });
    console.log(form);
}

function getClosestTaskId(el) {
    let id = $(el).closest('.wrapper').attr("id");
    id = parseInt(id.replace('task-', ''));
    return id;
}


function underConstruction() {
    $('#undermodal').modal('show');
}

