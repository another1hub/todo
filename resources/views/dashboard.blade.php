@extends('layout')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
                        {{ auth()->user()->name }} you are Logged In
                    </div>
                </div>
                <hr>
                <div class="card border-secondary">
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data" id="addtaskform" action="{{ url('save') }}">
                            <div class="mb-3">
                                <label for="inputTitle" class="form-label">Title</label>
                                <input name="title" type="text" class="form-control" id="inputTitle"
                                       aria-describedby="titleHelp">
                            </div>
                            <div class="mb-3">
                                <label for="inputDesc" class="form-label">Description</label>
                                <input name="description" type="text" class="form-control" id="inputDesc"
                                       aria-describedby="descHelp">
                            </div>
                            <div class="mb-3">
                                <label for="inputTags" class="form-label">Tags</label>
                                <input type="text" class="form-control" id="inputTags" aria-describedby="tagsHelp"
                                       placeholder="tag1 tag2 tag3">
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="file" name="image" placeholder="Choose image" id="image">
                                        @error('image')
                                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary" id="add-task-btn">Submit</button>
                        </form>
                    </div>
                </div>

                <hr>
                <div id="tasks">
                </div>
            </div>
        </div>
    </div>

    <div class="template invisible wrapper">
        <div class="card border-secondary mt-2">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-8">
                        <h5 class="card-title task-title">Task 1 title</h5>
                        <p class="card-text task-desc">Some quick example text to build on the card title and make up
                            the bulk of the card's content.</p>
                        <div class="pt-2">
                            <a href="#" class="btn btn-primary btn-sm"
                               onClick="showModalUpdateTask(this); return false;">edit</a>
                            <a href="#" class="btn btn-danger btn-sm"
                               onClick="deleteTask(this); return false;">delete</a>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card border-secondary" style="width: 200px;">
                            <div class="card-body">
                                <a class="task-img" href="" target="_blank"><img class="task-thumb" src=""></a>
                                <div class="form-group">
                                    <form class="jform" name="jform" enctype="multipart/form-data">
                                        <input type="file" name="image" placeholder="Choose image" id="image"
                                               class="input-file-cust" onchange="ajaxLoadFile(this); return false;">
                                    </form>
                                </div>
                                <a href="#" class="card-link" onClick="deleteTaskImg(this); return false;">delete</a>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div>
                    <a onClick="underConstruction(this); return false;" href="#" class="badge badge-primary">tag_1</a>
                    <span><a onClick="underConstruction(this); return false;" href="#">x</a></span> &nbsp;
                    <a onClick="underConstruction(this); return false;" href="#" class="badge badge-primary">tag_2</a>
                    <span><a onClick="underConstruction(this); return false;" href="#">x</a></span> &nbsp;
                    <a onClick="underConstruction(this); return false;" href="#" class="badge badge-primary">tag_3</a>
                    <span><a onClick="underConstruction(this); return false;" href="#">x</a></span> &nbsp;
                    <a onClick="underConstruction(this); return false;" href="#" class="badge badge-primary">tag_4</a>
                    <span><a onClick="underConstruction(this); return false;" href="#">x</a></span> &nbsp;
                    <a onClick="underConstruction(this); return false;" href="#" class="badge badge-primary">tag_5</a>
                    <span><a onClick="underConstruction(this); return false;" href="#">x</a></span> &nbsp;
                    <a onClick="underConstruction(this); return false;" href="#" class="badge badge-secondary">Добавить
                        тег</a>
                </div>
            </div>
        </div>
    </div>
@endsection


<div id="mymodal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="inputTitle2" class="form-label">Title</label>
                    <input name="title" type="text" class="form-control" id="inputTitle2" aria-describedby="titleHelp">
                </div>
                <div class="mb-3">
                    <label for="inputDesc2" class="form-label">Description</label>
                    <input name="description" type="text" class="form-control" id="inputDesc2"
                           aria-describedby="descHelp">
                </div>
                <input type="hidden" id="current-task-id" value="0">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="update-task">Save changes</button>
            </div>
        </div>
    </div>
</div>

<div id="undermodal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Under construction</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>this option is under construction</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

