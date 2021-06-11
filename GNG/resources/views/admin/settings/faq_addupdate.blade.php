@extends('admin_layouts/main')
@section('pageSpecificCss')
<link href="{{asset('assets/bundles/datatables/datatables.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet">

<link href="{{asset('assets/bundles/summernote/summernote-bs4.css')}}" rel="stylesheet">
<link href="{{asset('assets/bundles/izitoast/css/iziToast.min.css')}}" rel="stylesheet">

<style type="text/css">
  .ul-font>li {
    font-weight: 400;
    font-size: 15px;
  }

  .form-group {
    margin-bottom: 15px !important;
  }
  textarea.form-control {
    height: 80px !important;
  }
  .faqdiv{
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
  }
</style>

@stop
@section('content')
<section class="section">
  <div class="section-body">

<div class="row">
  <div class="col-12 col-md-12 col-lg-12">
    <div class="card">
      <div class="card-body">
        <div class="card-header">
          <h4>{{$title}} FAQ</h4>
        </div>
        <div class="card-body">
          <form class="forms-sample" id="addUpdateFAQ">
            {{ csrf_field() }}

            @if(empty($data))
            <div class="form-row  mb-5 p-3 faqdiv">

              <div class="form-group col-md-11">
                <label for="question">Question</label>

                <div class="form-group mb-2 mr-sm-2">
                  <input type="text" name="question[0]" placeholder="Question" class="form-control question" value="">
                </div>                  
              </div>

              
              <div class="form-group col-md-1">
              <label for="add_faq"></label>
                <div class="buttons mt-2">
                  <a class="btn btn-info text-light add_faq"><i class="fa fa-plus"></i></a>
                </div>
              </div>

              <div class="form-group col-md-11">
                <label for="answer">Answer</label>

                <div class="form-group mb-2 mr-sm-2">
                  <textarea name="answer[0]" placeholder="Answer" class="form-control answer" ></textarea>
                </div>                  
              </div>
                
            </div>
            
            <input type="hidden" id="hidden_count" value="1">
      
          </div>
          @else

          <div class="form-row">

              <div class="form-group col-md-11">
                <label for="question">Question</label>

                <div class="form-group mb-2 mr-sm-2">
                  <input type="text" name="question" placeholder="Question" class="form-control question" value="{{$data['question']}}">
                </div>                  
              </div>

              <div class="form-group col-md-11">
                <label for="answer">Answer</label>

                <div class="form-group mb-2 mr-sm-2">
                  <textarea name="answer" placeholder="Answer" class="form-control answer" >{{$data['answer']}}</textarea>
                </div>                  
              </div>
                
            </div>
      
          </div>
          @endif
            <input type="hidden" name="id" id="id" value="@if($data){{$data['id']}}@endif">
            <input type="hidden" name="action" id="action" value="@if($data){{'update'}}@else{{'add'}}@endif">
            <button type="submit" class="btn btn-primary mr-2">Submit</button>
            <a class="btn btn-light" href="{{route('faq/list')}}">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>

  </div>
</section>

@endsection
@section('pageSpecificJs')

<script src="{{asset('assets/bundles/jquery-ui/jquery-ui.min.js')}}"></script>
<script src="{{asset('assets/bundles/izitoast/js/iziToast.min.js')}}"></script>

<script>

  $(document).ready(function() {

    $("form").on('click', '.add_faq', function (e) {
      var counter = $("#hidden_count").val();
      counter = parseInt(counter);
      var html = $(".faqdiv:first").clone();
      $(html).find('input').val('');
      $(html).find('textarea').val('');
      $(html).find('textarea').text('');
      $(html).find('.question').attr('name','question['+counter+']');
      $(html).find('.answer').attr('name','answer['+counter+']');
      $(html).find('label.error').remove();
      $(html).find('.add_faq').removeClass('btn-info');
      $(html).find('.add_faq').addClass('btn-danger');
      $(html).find('.add_faq').html('<i class="fa fa-minus"></i>');
      $(html).find('.add_faq').addClass('remove_faq');
      $(html).find('.add_faq').removeClass('add_faq');
      $(html).insertAfter(".faqdiv:last");
     
      $('input[name="question['+counter+']"]').rules("add", {
        required: true,
        messages: {
          required: "Please Enter Question",
        },
      });

      
      $('input[name="answer['+counter+']"]').rules("add", {
        required: true,
        messages: {
          required: "Please Enter Answer",
        },
      });

      $("#hidden_count").val(counter+1);

    });

    $(document).on('click', '.remove_faq', function (e) {
      $(this).parent().parent().parent().remove();
    });


    $("#addUpdateFAQ").validate({
      rules: {
        "question[0]": {
          required: true,
        },
        "answer[0]": {
          required: true,
        },
      },
      messages: {
        "question[0]": {
          required: "Please Enter Question",
        },
        "answer[0]": {
          required: "Please Enter Answer",
        },
      },
    });

  $(document).on('submit', '#addUpdateFAQ', function (e) {
      e.preventDefault();
      var formdata = new FormData($("#addUpdateFAQ")[0]);
      $('.loader').show();
      $.ajax({
          url: '{{ route("addUpdateFAQ") }}',
          type: 'POST',
          data: formdata,
          dataType: "json",
          contentType: false,
          cache: false,
          processData: false,
          success: function (data) {
              $('.loader').hide();
              if (data.success == 1) {
                window.location.href = '{{ route("faq/list") }}';
                iziToast.success({
                  title: 'Success!',
                  message: data.message,
                  position: 'topRight'
                });
              } else {
                iziToast.error({
                  title: 'Error!',
                  message: data.message,
                  position: 'topRight'
                });
              }
          },
          error: function (jqXHR, textStatus, errorThrown) {
              alert(errorThrown);
          }
      });
    });
           
  });

</script>

@endsection