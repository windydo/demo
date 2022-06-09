<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <!-- CSS only -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    </head>
    <body class="antialiased">
        <div class="container">
            <div >
                <form>
                    <div class="mt-2 text-sm">
                    URL:<input type="text" name="url">
                    <button type="button" class="url-btn">查詢</button>
                    </div>
                <form>
            </div>
            <div class="url_info">

            </div>
        </div>
    </body>
</html>
<script>
    $(".url-btn").on("click",function(){
        var tform=$(this).closest("form");
        var ajax_url="/api/html/mine";
        var params=$(tform).serialize();
        $(".url_info").html('');
        $.post(ajax_url,params,"","json").done(function(rs){

            if(rs.ret==0){
                alert('網址錯誤或查無資訊');
            }
            else if(rs.ret==1){
                var text=`
                <div class="row">
                        <div class="col-xs-2 pr-2 text-primary">Image</div>
                        <div class="col-xs-8"><img src="${rs.data.image}" width="200"></div>
                </div>
                <div class="row">
                        <div class="col-xs-2 pr-2 text-primary">Title</div>
                        <div class="col-xs-8">${rs.data.title}</div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-xs-2 pr-2 text-primary">Description</div>
                        <div class="col-xs-8">${rs.data.description}</div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-xs-2 pr-2 text-primary">Created At</div>
                        <div class="col-xs-8">${rs.data.created_at}</div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-xs-2 pr-2 text-primary">Screenshot</div>
                        <div class="col-xs-8"></div>
                    </div>
                `;
                $(".url_info").html(text);
            }
            else {
                alert("網路發生錯誤，請稍後再試");
            }
        });
    });
</script>

