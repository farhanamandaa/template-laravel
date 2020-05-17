@extends('layout')

@section('content')
<section class="section">
    <div class="section-header">
      <h1>Kategori</h1>
    </div>
  </section>
  <div class="row">
      <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-right">
                    <button class="btn btn-primary" onclick="$('#fire-modal-1').modal()" type="button">Tambah Kategori</button>
                </div>
              {{-- <h4>Daftar Kategori</h4> --}}
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="table-kategori">
                        <thead class="dark">
                            <tr>
                                <th>No</th>
                                <th>Nama Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $index => $category)
                                    <tr id="tr-{{$category->id}}">
                                    <td>{{$index+1}}</td>
                                    <td>{{$category->name}}</td>
                                    <td>
                                        <div class="btn-toolbar">
                                            <button type="button" class="btn btn-warning"><i class="fa fa-edit"></i> Edit</button>
                                            <button type ="button" onclick="deleteCategory({{$category->id}})" class="btn btn-danger"><i class="fa fa-times"></i> Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
          </div>
      </div>
  </div>
  <div class="modal fade" tabindex="-1" role="dialog" id="fire-modal-1" aria-hidden="true" style="display: none;">       
        <div class="modal-dialog modal-md" role="document">         
            <div class="modal-content">           
                <div class="modal-header">             
                    <h5 class="modal-title">Modal Title</h5>             
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">               
                        <span aria-hidden="true">×</span>             
                    </button>           </div>           
                    <div class="modal-body"> 
                        <form action="/categories" method="POST" id="form-submit">          
                        <div class="form-group">
                            @csrf
                            <label for="">Nama Kategori</label>
                            <input type="text" class="form-control" name="name">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" id="btn-submit">Submit</button>    
                    </div>         
                    </form>
                </div>       
            </div>    
        </div>
    </div>

<script>
    $("#table-kategori").DataTable()
    function appendData()
    {
        $("#table-kategori tr:last").after(`
        <tr>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        `)
    }
    $("#form-submit").submit(function(event){
        event.preventDefault()
        var form = $(this);
        var mydata = new FormData(this);
        $.ajax({
            type: "POST",
            url : '/categories',
            data : mydata,
            processData: false,
            contentType: false,
            beforeSend : function(){
                $("#btn-submit").attr('disabled',true)
            },
            success: function(response, textStatus, xhr) {
                var nama_kategori = response.data.name
                var id_kategori = response.data.id
                var nomor = response.data.number
                $("#table-kategori tr:last").after(`
                <tr id="tr-${id_kategori}">
                    <td>${nomor}</td>
                    <td>${nama_kategori}</td>
                    <td>
                        <div class="btn-toolbar">
                            <button type="button" class="btn btn-warning"><i class="fa fa-edit"></i> Edit</button>
                            <button type ="button" onclick="deleteCategory(${id_kategori})" class="btn btn-danger"><i class="fa fa-times"></i> Delete</button>
                        </div>
                    </td>
                </tr>
                `)
                $("#btn-submit").attr('disabled',false)
                $("#fire-modal-1").modal('hide')
            },
            error: function(xhr, textStatus, errorThrown) {
                $("#btn-submit").attr('disabled',false)
                alert('error')
            }
        })
    })

    function deleteCategory(id)
    {
        var konfirmasi = confirm('Apakah Anda yakin akan menghapus kategori ini ?')
        if(konfirmasi == true)
        {
            $.get("/categories/"+id,function(){
                $("#tr-"+id).remove()
            })  

            $.ajax({
                type: "GET",
                url : '/categories'+id,
                
                beforeSend : function(){
                    
                },
                success: function(response, textStatus, xhr) {
                    $("#tr-"+id).remove()
                },
                error: function(xhr, textStatus, errorThrown) {
                    
                }
            })
        }
    }
</script>
@endsection
