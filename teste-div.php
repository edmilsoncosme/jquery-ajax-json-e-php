<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <style>
            *{margin: 0; padding: 0; box-sizing: border-box; font-family: 'Arial', sans-serif}
            .register{display: block; width: 100%; max-width: 600px; border: 15px solid #fff; margin: 0 auto; padding: 20px; background: #eee;}
            .register header{margin-bottom: 20px; text-align: center; padding-bottom: 20px; border-bottom: 2px solid #ccc;}
            .register header p{margin: 5px 0 10px 0;}
            .openform{float: right; background: #00cc33; display: inline-block; font-size: 0.9em; margin-left: 10px;  border: 2px solid #fff; outline: 2px solid #00cc33; padding: 5px 10px; cursor: pointer; color: #fff; text-transform: uppercase; margin-top: 10px;}
            .openform:before{content: '+';}
            .openform.closeform{background-color: #b25c5c; outline-color: #b25c5c; padding: 5px 12.5px;}
            .openform.closeform:before{content: '-';}
            .register form{display: none; margin-bottom: 30px;}
            .register input{width: 100%; padding: 10px; margin-bottom: 10px;}            
            .register button{background: #09f; border: 2px solid #fff; outline: 2px solid #09f; padding: 10px; cursor: pointer; color: #fff; text-transform: uppercase; margin-top: 10px;}
            .register .buttonUpdate{background: #09f; border: 2px solid #fff; outline: 2px solid #09f; padding: 10px; cursor: pointer; color: #fff; text-transform: uppercase; margin-top: 10px;}                                    
            .register .close{background: #cc0033; font-size: 0.9em; margin-left: 10px;  border: 2px solid #fff; outline: 2px solid #cc0033; padding: 10px; cursor: pointer; color: #fff; text-transform: uppercase; margin-top: 10px;}
            .user_box{display: block; padding: 10px; background: #fbfbfb; margin-top: 20px; padding-top: 20px; border-top: 1px dotted #000;}
            .action{cursor: pointer; display: inline-block; margin-top: 10px; padding: 5px 10px; font-size: 0.7em; margin-right: 10px; text-transform: uppercase; background: #555; color: #fff;}
            .del{background: #a72626;}
            .edit{background: #006699;}
            .form_load{display: none; vertical-align: middle; margin-left: 15px; margin-top: -2px;}
            .trigger{display: none; text-transform: uppercase; padding: 15px; background: #ccc; color: #000; margin-bottom: 20px; font-size: 0.8em; font-weight: bolder}
            .trigger-error{background: #e4b4b4;}
            .trigger-success{background: #b4e4b9;}
            .trigger-box-delete{background: #e4b4b4;}
            .loamore{display: inline-block; margin-top: 25px; text-transform: uppercase; font-size: 0.7em; background: #555; color: #fff; padding: 10px; cursor: pointer;}

        </style>
        <meta charset="UTF-8">
        <title>Teste div</title>
    </head>
    <body>
        <div class="trigger-box-delete"></div>       
        <a class="action edit j_alterar_div" >Alterar div</a>
        <script src="js/jquery.js"></script>
        <script src="js/script_teste.js"></script>
    </body>
</html>
