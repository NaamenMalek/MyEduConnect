<?php

namespace App\Http\Controllers\pdf;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\User;
use Barryvdh\DomPDF\PDF;
use DateTime;

class GenratePdfController extends Controller
{
    public function generatePDF(int $id)
    {

     $user= User::find($id);
     $Date = new DateTime();
     $classe=Classe::where("id",$user->classe_id)->with("Specialite")->first();


     $niveau= $classe->specialite['niveau'];
     //Niveau de etudiant
     $niveau_francais='';
      if($niveau==1){
       $niveau_francais="premiere";
      }else if($niveau==2){
        $niveau_francais="deuxieme" ;
      }else{
        $niveau_francais="troisime" ;
      }

     //Classe de etudiant
      $classe_current=$classe->nom;
      $specaialite=$classe->specialite['type'];

       //Specaialite de etudiant
      $specaialite_francais='';
        if($specaialite=='DSI'){
        $specaialite_francais="Développement des Systèmes d'Information";
       }else if($specaialite=='RSI'){
         $specaialite_francais="Réseau des Systèmes d'Information" ;
       }else{
         $specaialite_francais="Systéme embaqrué" ;
       }

         //Image de Signature et cachet
        $file = "../resources/images/Signature_cachet.PNG";
        $img = file_get_contents($file);
        $base64 = base64_encode($img);

        //Image de Barre de Iset
        $file_iset = "../resources/images/IsetBare.PNG";
        $img_iset = file_get_contents($file_iset);
        $base64_iset = base64_encode($img_iset);


      $data=[
        'date'=>'Fait à Bizerte le '.$Date->format('Y-m-d'),
        'user'=>$user,
        'niveau'=>$niveau_francais,
        'classe_current'=>$classe_current,
        'specaialite'=>$specaialite_francais,
        'base64'=>$base64,
        'bareIset'=>$base64_iset
      ];


        $pdf = app()->make(PDF::class);
        $pdf->loadView('pdf.document',$data);
        //$pdf->setOptions(['isRemoteEnabled' => true]);
        return $pdf->download('Deamande_Stage.pdf');
    }
}
