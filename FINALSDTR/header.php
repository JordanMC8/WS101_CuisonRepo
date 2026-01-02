<?php

    include("config.php");
?>

<!DOCTYPE html>
    <html lang="en">
    <style>
        html, body{
            height: 100%;
            background-color: #8f6ddfff;
        }
        body{
            box-sizing: border-box;
            margin: 0; 
            font-family: Arial, Helvetica, sans-serif;
            font-size: 1em;
            font-weight: bolder;
            display: flex;
            flex-direction: column;
        }
        .top, .bottom{
            text-align: center;
            padding: 0.5em 0;
            background-color: #404566ff;
            color: white;
            text-shadow: 0px 1px 4px #000000;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2),
                        0 6px 20px rgba(0,0,0,0.19);
        }

        .bottom{
            box-shadow: 0 -4px 8px rgba(0,0,0,0.2),
                        0 -6px 20px rgba(0,0,0,0.19);
        }
        .spacer{
            flex: auto;
            border: 5px solid #8f6ddfff;
            background-color: #8f6ddfff;
            margin: 2em 15%;
            /* box-shadow: 0 4px 8px rgba(0,0,0,0.2),
                        0 6px 20px rgba(0,0,0,0.19); */
            text-shadow: 0px 1px 4px #000000;
            color: white;
            border-radius: 10px;
            padding: 0.5em 0;
        }
        .forms{
            display: flex;
            flex-direction: column;
            align-items: center;    
            width: 100%;
        }

        .grid-form{
            display: grid;
            grid-template-columns: 180px 500px;
            gap: 20px 25px;
            align-items: center;
        }
        .grid-form input{
            width: 100%;
            padding: 10px 14px;
            font-size: 1.1em;
            border: 2px solid #000000ff;
            border-radius: 10px;
            box-sizing: border-box;
        }
        .button {
            font-size: 1em;
            font-weight: bolder;
            grid-column: span 2;
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }
        .button input[type="submit"] {
            width: 100%;
            max-width: 300px;
            background-color: #404566ff;
            color: white;
            padding: 12px 30px;
            font-size: 1.1em;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.2s ease-in-out;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2),
                        0 6px 20px rgba(0,0,0,0.19);
        }
        .button input[type="submit"]:hover {
            background-color: #4b5596ff;   
        }
        .button input[type="submit"]:active {
            background-color: #4959c0ff; 
            transform: scale(0.97);
        }
        a{
            color: #ffffffff;
        }
        .password-wrapper {
            position: relative;
            width: 100%;
        }
        .password-wrapper input {
            width: 100%;          
            padding-right: 40px;  
            border-radius: 10px; 
        }
        .password-wrapper .toggle-eye {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #4b5596ff;
            font-size: 1.1em;
        }
        .password-wrapper .toggle-eye:hover {
            color: #4b5596ff;
        }
        .dtr{
            flex: auto;
            flex-direction: column;
            border: 3px solid ##8f6ddfff;
            background-color: ##8f6ddfff;
            margin: 2em 2em;
            /* box-shadow: 0 4px 8px rgba(0,0,0,0.2),
                        0 6px 20px rgba(0,0,0,0.19); */
            text-shadow: 0px 1px 4px #000000;
            color: white;
            border-radius: 10px;
            padding: 0.5em 0;
        }
        .display{
            flex: auto;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 1em;
        }
        .box2{
            display: flex;
            flex-direction: row;
            margin-top: 1em;
        }
        .data {
            width: 75%;
            padding: 2em;
            display: grid;
            place-items: start;
            overflow-x: auto;
        }
        .data table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
            background-color: #fff0;
            border: 2px solid gray;
        }
        .data table tr:first-child td {
            font-weight: bold;
            padding: 10px 0;
        }
        .data td {
            padding: 10px;
        }
        .data table tr:nth-child(odd) {
            background-color: rgba(255,255,255,0.3);
        }
        .data table tr:nth-child(even) {
            background-color: rgba(255,255,255,0.15);
        }
        .options {
            width: 25%;
            display: flex;
            flex-direction: column;
            justify-content: center;  
            align-items: center;       
            text-align: center;
            padding: 2em;
            gap: 1em;                  
        }
        .options h4 {
            margin-top: 1em;
            line-height: 1.8em;
        }
        .options h4 a {
            color: inherit;
            text-decoration: none;
        }
        .options h4 a:hover {
            text-decoration: underline;
        }
        .search-bar {
            gap: 10px;        
        }
        .search-input {
            padding: 10px 14px;
            font-size: 1em;
            border-radius: 8px;
            border: 2px solid #ffffffff;
            outline: none;
            width: 220px;
            background-color: #ffffffff;
            color: #333;
            transition: 0.2s ease-in-out;
            box-shadow: 0 2px 4px rgba(0,0,0,0.15);
        }
        .search-input:focus {
            border-color: #ffffffff;
            background-color: #ffffffff;
        }
        .search-btn,
        .add-btn {
            padding: 10px 18px;
            font-size: 0.95em;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            color: white;
            background-color: #404566ff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            transition: 0.2s ease;
        }
        .search-btn:hover,
        .add-btn:hover {
            background-color: #4b5596ff;
        }
        .search-btn:active,
        .add-btn:active {
            transform: scale(0.96);
        }
        .add-btn {
            background-color: #404566ff;
        }
        .add-btn:hover {
            background-color: #4b5596ff;
        }
        .custom-checkbox input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }
        .custom-checkbox {
            display: inline-flex;
            align-items: center;
            cursor: pointer;
            position: relative;
            height: 22px;
            width: 22px;
        }
        .custom-checkbox .checkmark {
            height: 22px;
            width: 22px;
            background-color: #e8f2f4;
            border: 2px solid #403768ff;
            border-radius: 6px;
            display: inline-block;
            transition: 0.2s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .custom-checkbox:hover .checkmark {
            border-color: #5F8F8D;
        }
        .custom-checkbox input:checked + .checkmark {
            background-color: #76A8A6;
            border-color: #5F8F8D;
        }
        .custom-checkbox .checkmark::after {
            content: "";
            position: absolute;
            display: none;
        }
        .custom-checkbox input:checked + .checkmark::after {
            display: block;
        }
        .custom-checkbox .checkmark::after {
            left: 7px;
            top: 3px;
            width: 6px;
            height: 12px;
            border: solid white;
            border-width: 0 3px 3px 0;
            transform: rotate(45deg);
        }
        .header-with-icon {
            display: flex;
            align-items: center;
            gap: 6px;
            justify-content: center;
        }
        .sort-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            margin: 0;
        }
        .sort-btn i {
            font-size: 14px;
            color: white;
            transition: 0.2s;
        }
        .sort-btn:hover i {
            color: #ddd;
        }
    </style>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Finals - DTR System</title>
    </head>
    <body>
        <div class="top">
            <h2>Finals - DTR System</h2>
        </div>
	