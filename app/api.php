<?php
 
    //
    header('Content-Type: application/json');

    //
    require '../vendor/autoload.php';
    require 'v1/functions.php';

    //
    if(isset($_REQUEST['token'])) {

        //
        if(isset($_REQUEST['app_id'])) {
    
            //
            switch ($_REQUEST['domain']) {

                //
                case 'persons': require 'endpoint-identity-persons.php'; break;

                //
                default: header("Location: template-guest-hello.php");
            
            }

        } else { 

            // connect to the PostgreSQL database

            $data[] = NULL;
            $code = 401;
            $message = "Forbidden - Valid App ID required";

            $results = array(
                'status' => $code,
                'message' => $message,
                'data' => $data,
                /*
                'log' => [
                    'process' => $process_id = Token::process_id(),
                    'event' => $event_id = Token::event_id($process_id)
                ]*/
            );
            
            $results = json_encode($results);
        
            echo $results;
        
        }

    } else {

        // connect to the PostgreSQL database

        $data[] = NULL;
        $code = 401;
        $message = "Forbidden - Valid token required";

        $results = array(
            'status' => $code,
            'message' => $message,
            'data' => $data,
            /*
            'log' => [
                'process' => $process_id = Token::process_id(),
                'event' => $event_id = Token::event_id($process_id)
            ]*/
        );

        $results = json_encode($results);
        
        echo $results;

    }

?>
