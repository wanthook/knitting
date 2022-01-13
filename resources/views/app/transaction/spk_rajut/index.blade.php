@extends('adminlte::page')

@section('title', 'SPK Rajut')

@section('content_header')
    <h1>SPK Rajut</h1>
@stop

@section('content')
<div class="modal fade" id="modal-form-upload">
    <div class="modal-dialog">
        <div class="modal-content bg-secondary">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-upload"></i>Form Upload SPK Rajut</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_data_upload" action="{{route('spkrajutupload')}}" accept-charset="UTF-8" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="modal-body">   
                <div class="form-group">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="kode">File</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="formUpload" name="formUpload">
                                        <label class="custom-file-label" for="formUpload">Choose file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="cmdUpload">Upload</span>
                                    </div>
                                </div>
                            </div>
                        </div>  
                        <div class="col-12">
                            <a class="btn btn-info btn-xs" href="{{route('app.files', 'file_template_spkrajut')}}" target="_blank"><i class="fa fa-download"></i>Template Document</a>
                        </div>
                    </div>
                </div>
            </div>   
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="card card-primary card-outline">
    <div class="card-header">
        <div class="row">
            <div class="col-3">
                <div class="form-group">                                        
                    <span class="label label-default">No. SPK</span>
                    <input id="sSearch" class="form-control form-control-sm" name="sSearch" type="text">
                </div>
            </div>
            <div class="col-2">
                <div class="form-group">                                        
                    <span class="label label-default">Tanggal</span>
                    <input id="stanggal" class="form-control form-control-sm" name="stanggal" type="date">
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">                                        
                    <span class="label label-default">Customer</span>
                    <select id="scustomer" class="form-control form-control-sm select2" name="scustomer"></select>
                </div>
            </div>
            <div class="col-3">
                <div class="btn-group">
                    <button class="btn btn-sm btn-primary" id="sCmd"><i class="fa fa-search"></i>&nbsp;Cari</button>
                    <a class="btn btn-sm btn-success" alt="Tambah" href="{{route('spkrajutadd')}}"><i class="fa fa-plus-circle"></i>&nbsp;Tambah</a>
                    <button class="btn btn-sm btn-warning" alt="Upload" data-toggle="modal" data-target="#modal-form-upload"><i class="fa fa-file-upload"></i>&nbsp;Upload</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.card-header -->
        <div class="card-body">  
            <table id="tables" class="table table-hover">
                <thead>
                    <tr>
                        <th></th>
                        @php
                        $lstTbl = [
                            'tspk' => 'No SPK', 
                            'ttanggal' => 'Tanggal SPK', 
                            'tstatus' => 'Status SPK', 
                            'tstatustanggal' => 'Tanggal Status', 
                            'tcustomercode' => 'Kode Customer', 
                            'tcustomername' => 'Nama Customer', 
                            'ttotqty' => 'Total Qty'
                        ];
                        
                        foreach($lstTbl as $k => $v)
                        {
                            echo '<th class="'.$k.'">'.$v.'</th>';
                        }
                        @endphp
                    </tr>
                </thead>
            </table>
        </div>
    <!-- /.card-body -->
</div>
@stop

@section('js')    
    <script>
        var tables = null;
        $(function(e)
        {
            bsCustomFileInput.init();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            tables = $('#tables').DataTable({
                "sPaginationType": "full_numbers",
                "searching":false,
                "ordering": true,
                "deferRender": true,
                "processing": true,
                "serverSide": true,
                "autoWidth": false,
                "scrollX": true,
                "lengthMenu": [100, 500, 1000, 1500, 2000 ],
                "ajax":
                {
                    "url"       : "{{ route('spkrajuttable') }}",
                    "type"      : 'POST',
                    data: function (d) 
                    {
                        d.search     = $('#sSearch').val();
                        d.stanggal     = $('#stanggal').val();
                        d.scustomer     = $('#scustomer').val();
                    }
                },
                "columnDefs"    :[
                {
                    "targets": 0,
                    "className":      'btndel',
                    "orderable":      false,
                    "data"     :           null,
                    "defaultContent": '<div class="btn-group"><button class="btn btn-sm btn-primary btnedit" data-toggle="modal" data-target="#modal-form"><i class="fa fa-edit"></i></button><button class="btn btn-sm btn-danger btndelete"><i class="fa fa-eraser"></i></button></div>'
                },
                {
                        targets : 'tspk',
                        data: "no"
                },
                {
                        targets : 'ttanggal',
                        data: "tanggal"
                },
                {
                        targets : 'tcustomercode',
                        data: "customers.kode"
                },
                {
                        targets : 'tcustomername',
                        data: "customers.nama1"
                },
                {
                        targets : 'ttotqty',
                        data: "total_qty"
                },
                {
                        targets : 'tstatus',
                        data: function(rows)
                        {
                            switch(rows.status)
                            {
                                case 'unprocessed': return '<p class="text-muted">Belum Diproses</p>'; break;
                                case 'processed': return '<p class="text-primary">Diproses</p>'; break;
                                case 'finished': return '<p class="text-success">Selesai</p>'; break;
                                case 'cancelled': return '<p class="text-danger">Dibatalkan</p>'; break;
                            }
                        }
                },
                {
                        targets : 'tstatustanggal',
                        data: "status_at"
                }]
            });

            $('#tables tbody').on('click', '.btnedit', function () 
            {
                var tr = $(this).closest('tr');
                var row = tables.row( tr );
                var datas = row.data();
                
                window.location = "{{route('spkrajutedit', '')}}/"+datas.id;

                
            });
            
            $('#tables tbody').on('click', '.btndelete', function () 
            {
                var tr = $(this).closest('tr');
                var row = tables.row( tr );
                var datas = row.data();
                
                if(confirm('Apakah Anda yakin menghapus data ini?'))
                {
                    $.ajax(
                    {
                        url         : '{{route("spkrajutdelete")}}',
                        dataType    : 'JSON',
                        type        : 'POST',
                        data        : {id : datas.id} ,
                        beforeSend  : function(xhr)
                        {
                            toastOverlay.fire({
                                icon: 'warning',
                                title: 'Sedang memproses hapus data',
                                onBeforeOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        },
                        success(result,status,xhr)
                        {
                            if(result.status == 1)
                            {
                                Toast.fire({
                                    icon: 'success',
                                    title: result.msg
                                });
                            }
                            else
                            {
                                if(Array.isArray(result.msg))
                                {
                                    var str = "";
                                    for(var i = 0 ; i < result.msg.length ; i++ )
                                    {
                                        str += result.msg[i]+"<br>";
                                    }
                                    Toast.fire({
                                        icon: 'error',
                                        title: str
                                    });
                                }

                            }
                            tables.ajax.reload();
                        },                        
                        error: function(jqXHR, textStatus, errorThrown) { 
                            /* implementation goes here */ 
                            toastOverlay.close();
                            console.log(jqXHR.responseText);
                        }
                    });

                    return false;
                }
            });

            $('#sCmd').on('click', function(e)
            {
                tables.ajax.reload()
            });
            
            // $('#form_data').submit( function(e)
            // {
            //     e.preventDefault();
            //     const data = $(this).serialize();
                
            //     $.ajax(
            //     {
            //         url         : $(this).attr('action'),
            //         dataType    : 'json',
            //         type        : 'POST',
            //         data        : data ,
            //         success(result,status,xhr)
            //         {
            //             if(result.status == 1)
            //             {
            //                 reset();
                            
            //                 Toast.fire({
            //                     icon: 'success',
            //                     title: result.msg
            //                 });
            //             }
            //             else
            //             {
            //                 if(Array.isArray(result.msg))
            //                 {
            //                     var str = "";
            //                     for(var i = 0 ; i < result.msg.length ; i++ )
            //                     {
            //                         str += result.msg[i]+"<br>";
            //                     }
            //                     Toast.fire({
            //                         icon: 'error',
            //                         title: str
            //                     });
            //                 }
            //                 else
            //                 {
            //                     Toast.fire({
            //                         icon: 'error',
            //                         title: result.msg
            //                     });
            //                 }
                            
            //             }
            //             tables.ajax.reload();
            //         },
            //         error: function(jqXHR, textStatus, errorThrown) { 
            //             /* implementation goes here */                        
            //             console.log(jqXHR.responseText);
            //         }
                    
            //     });
                
            //     return false;
            // });
            
            // $('#cmdUpload').on('click', function(e)
            // {
            //     let frm = document.getElementById('form_data_upload');
            //     let datas = new FormData(frm);

            //     $.ajax(
            //     {
            //         url         : $('#form_data_upload').attr('action'),
            //         dataType    : 'JSON',
            //         type        : 'POST',
            //         data        : datas ,
            //         processData: false,
            //         contentType: false,
            //         beforeSend  : function(xhr)
            //         {
            //             toastOverlay.fire({
            //                 icon: 'warning',
            //                 title: 'Sedang memproses data upload'
            //             });
            //         },
            //         success(result,status,xhr)
            //         {
            //             toastOverlay.close();
            //             if(result.status == 1)
            //             {
            //                 Toast.fire({
            //                     icon: 'success',
            //                     title: result.msg
            //                 });
            //             }
            //             else
            //             {
            //                 if(Array.isArray(result.msg))
            //                 {
            //                     var str = "";
            //                     for(var i = 0 ; i < result.msg.length ; i++ )
            //                     {
            //                         str += result.msg[i]+"<br>";
            //                     }
            //                     Toast.fire({
            //                         icon: 'error',
            //                         title: str
            //                     });
            //                     $('#tipe_exim').attr('disabled','disabled');
            //                 }
                            
            //             }
            //             tables.ajax.reload();
            //         },
            //         error: function(jqXHR, textStatus, errorThrown) { 
            //             toastOverlay.close();
            //             /* implementation goes here */ 
            //             console.log(jqXHR.responseText);
            //         }
            //     });
            // });
            
            // $('#modal-form').on('hidden.bs.modal', function (e) 
            // {
            //     reset();
            // });
            
            $('#scustomer').select2({
                placeholder: "",
                allowClear: true,
                minimumInputLength: 0,
                delay: 250,
                ajax: {
                    url: "{{route('customerselect')}}",
                    dataType    : 'json',
                    type : 'post',
                    data: function (params) 
                    {
                        var query = {
                            q: params.term
                        }
                        
                        return query;
                    },
                    processResults: function (data) 
                    {
                        return {
                            results: $.map(data.items, function(obj, index) {
                                return { id: index, text: obj.text + " - " + obj.nama};
                            })
                        };
                    },
                    cache: true
                }
            });
            
            // $('#group_id').select2({
            //     placeholder: "",
            //     allowClear: true,
            //     minimumInputLength: 0,
            //     delay: 250,
            //     ajax: {
            //         url: "{{route('masteroptionselectgroup')}}",
            //         dataType    : 'json',
            //         type : 'post',
            //         data: function (params) 
            //         {
            //             var query = {
            //                 q: params.term
            //             }
                        
            //             return query;
            //         },
            //         processResults: function (data) 
            //         {
            //             return {
            //                 results: data.items
            //             };
            //         },
            //         cache: true
            //     }
            // });
            
        });

        // function reset()
        // {
        //     document.getElementById("form_data").reset(); 
        //     $('#country_id').val("").trigger('change');
        //     $('#group_id').val("").trigger('change');
        //     tables.ajax.reload();
        // }
    </script>
@stop