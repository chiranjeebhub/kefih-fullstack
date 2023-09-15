<?php

namespace App\Helpers;


use Illuminate\Http\Request;


class MsgHelper
{

    public static function save_session_message($res,$msg,$Request)
    {
			$Request->session()->flash('message.level', $res);
			$Request->session()->flash('message.content', $msg);
    }
	 public static function error_session_message($res,$msg,$Request)   
    {
       
        $html="
        <h6>Some Error From CSV</h6>
        <table>";
            $html.="<thead>
            <tr>
            <th>Row</th>
            <th>&nbsp;&nbsp;Error</th>
            </tr>
            </thead><tbody>";
        foreach($msg as $ms){
           $html.="<tr>
           <td>
           ".($ms['row'])."
           </td>
            <td>&nbsp;&nbsp;
           ".($ms['error_message'])."
           </td>
           </tr>"; 
        }
         $html.="</tbody></table>";
            $Request->session()->flash('message.level', $res);		
            $Request->session()->flash('message.content', $html);  
    }
	
	/*******************************/
	
	
	/*******************************/
	
	
}
