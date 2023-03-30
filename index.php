<?php

print "Que accion desea realizar: \n";
print "1.- Crear una carpeta nueva \n";
print "2.- Borrar una carpeta existente \n";
print "3.- Listar las carpetas \n";
print "4.- Buscar documento por titulo (nombre del documento sin su extension) \n";
print "5.- Buscar documento por codigo \n";
print "6.- Buscar documento por autor \n";
print "7.- Buscar documento por fecha \n";


$option = readline("Ingrese el numero correspondiente a su opcion: \n");

switch($option):
    case 1:
        $folder = readline("Ingrese el nombre de la carpeta que desea crear: \n");
        if(!is_dir($folder)):
            mkdir($folder);
            chmod($folder,0777);
        else:
            print "Ya existe \n";
        endif;
        break;
    case 2:
        $folder = readline("Que carpeta desea eliminar: \n");
        if(is_dir($folder)):
            rmdir($folder);
        else:
            print "No existe el directorio \n";    
        endif;    
        break;
    case 3:
        $list = opendir(__DIR__);
        while(false!==($folders=readdir($list))):
            if(is_dir($folders) AND $folders!='.' AND $folders!='..'):
                print $folders;
                print "\n";    
            endif;    
        endwhile;    
        break;
    case 4:
        $search = readline("Ingrese el nombre del documento(archivo): \n");
        searchDocument($search);
        break; 
    case 5:
        $code = readline("Indique el codigo del documento: \n");
        searchCode($code,$autor=null,$date=null);
        break;  
    case 6:
        $autor = readline("Ingrese el nombre del autor: \n");
        searchCode($code=null,$autor,$date=null);
    break; 
    case 7:
        print("Ejemplo de como debe poner la fecha: 2023-01-30 \n");
        $date = readline("Ingrese la fecha del documento:  \n");
        searchCode($code=null,$autor=null,$date);
        break;            
    default:
        print "Opcion invalida \n";
        break;            

endswitch;    



function searchDocument($search){

    $directorio=scandir(__DIR__);
    $archivos =  array_diff($directorio,array('.','..')); //Le puse otro nombre al usarlo en la otra function....cambiar luego

    foreach($archivos as $archivo):
        $extension= explode('.',$archivo);
        if(!is_dir($archivo) AND $extension[1]!='php' AND $extension[0]==$search):
            print "El nombre del archivo es: ".$archivo;
            print "\n";
            $infoDocument = readInformation($archivo);
            print "El contenido del archivo es: \n";
            foreach($infoDocument as $value):
                print $value;
            endforeach;   
            print "\n";

            break;
        endif;
     endforeach; 

}

function readInformation($archivo){

    $document=fopen($archivo,'r+');
    $lines=[];

    while($line = fgets($document)):
        $lines[]=$line;
    endwhile;

    fclose($document);

    return $lines;
}


function searchCode($code,$autor,$date){

    $contents =  array_diff(scandir(__DIR__),array('.','..'));
 

    foreach($contents as $content):
        
        $extension= explode('.',$content);
        if($extension[1]!=='php' AND !is_dir($content)):
           $document_imprimir = openDocumentCode($content,$code,$autor,$date); 
        endif; 
        if(!empty($document_imprimir)):
            $lines = textImpreso($document_imprimir);
            foreach($lines as $line):
                print $line;
                print "\n";
            endforeach;  
            break;       
        endif;  

    endforeach;    
}

function openDocumentCode($content,$code,$autor,$date){

    $docu = fopen($content,'r+');
    $count=0;
    while($line = fgets($docu)):
   
        if(preg_match("/#/",$line)):
            $count++;
        endif;    
        if($code!==null AND $count==2 AND trim($line) AND (int)$code==(int)$line):
            // var_dump($line);
            // die();
            return $content;
        endif; 
        if($autor!==null AND trim($line) AND  trim($autor)==trim($line)):
            return $content;
        endif;   
        if($date!==null AND trim($line) AND  trim($date)==trim($line)):
            return $content;
        endif;   

    endwhile;    
    fclose($docu);
    
}

function textImpreso($document_imprimir){

    $open = fopen($document_imprimir,'r+');
    $lines=[];
    while($line=fgets($open)):
        $lines[]=$line;
    endwhile; 
    fclose($open);
    return $lines;   

}