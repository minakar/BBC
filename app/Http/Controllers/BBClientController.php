<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\B_bcommande;
use Auth;
use App\User;
use DB;
use Illuminate\Support\Facades\Input;


use Notification;
use Illuminate\Notifications\Notifiable;

use App\Notifications\NouveauxBonDeCommande;

use App\Buser;use App\BoncommandeBloc;

class BBClientController extends Controller
{
    //



    public function showPDF($id)
    {

    $bcommande = B_bcommande::where("b_bcommandes.code", "=",$id) ->orderBy('code', 'desc')->get();
    $bbloc=BoncommandeBloc::where("boncommande_blocs.bcommande_code", "=",$id) ->get();
  return view('admin.teste-imprime-admin' ,['bcommande'=>$bcommande],['bbloc'=>$bbloc]);

    }

    public function BureauEtude(){

        $user=Auth::user()->id;

        $BureauEtude=DB::table('b_bcommandes')->where('client_id',"=",$user)->get();

return view('front.compte.showlistBureauEtude' ,['BureauEtude'=>$BureauEtude]);

        }
    
    public function store(Request $request)
                                             {
                                                //dd($request->all())  ;
                                              //  return $request->DateDebuttravaux;
                                           $request->aa=date("m-d-Y", strtotime($request->DateDebuttravaux));
                                               $data=[
                                                'client_id' =>$request->user()->id,
                                                
                                                'MaitreOuvrage' =>$request->MaitreOuvrage,
                                                'MaitreOuvrageAdr' =>$request->MaitreOuvrageAdr,
                                                'MaitreOuvrageRS' =>$request->MaitreOuvrageRS,
                                                'MaitreOuvrageNif' =>$request->MaitreOuvrageNif,
                                                'MaitreOuvrageTel' =>$request->MaitreOuvrageTel,
                                                'MaitreOuvrageFax' =>$request->MaitreOuvrageFax,
                                                'MaitreOuvrageEmail' =>$request->MaitreOuvrageEmail,
                                                 'maitre_oeuv' =>$request->user()->Nom,
                                                 'maitre_oeuvAdresse' =>$request->user()->Adresse,
                                                 'maitre_oeuvEmail' =>$request->user()->email,
                                                 'intitule_proj' =>$request->intitule_proj,
                                                 'CodeWilaya' =>$request->country,
                                                 'SurfaceConstruitM2' =>$request->SurfaceConstruitM2,
                                                 'DateDebuttravaux' => $request->aa,
                                                 'DateD??laisTravaux'  =>$request->DateD??laisTravaux,
                                                 'maitre_oeuv' =>$request->maitre_oeuv,
                                                 'maitre_oeuvT??l'=>$request->maitre_oeuvT??l,
                                                 'maitre_oeuvFax' =>$request->maitre_oeuvFax,
                                                 'maitre_oeuvAdresse' =>$request->maitre_oeuvAdresse,
                                                 'maitre_oeuvEmail' =>$request->maitre_oeuvEmail,
                                                'laboratoire' =>$request->laboratoire,
                                                 'laboratoireEmail' =>$request->laboratoireEmail,
                                                 'laboratoireAdresse' =>$request->laboratoireAdresse,
                                                 'laboratoireFax' =>$request->laboratoireFax,
                                                 'AvantProjet'=>$request->get('AvantProjet') ? 1 : 0,
                                                 'Esquisse'=>$request->get('Esquisse') ? 1 : 0,
                                                 'AvantProjet'=>$request->get('AvantProjet') ? 1 : 0,
                                                 'IngNumeroagrement' =>$request->IngNumeroagrement,
                                                 'IngControlleSite' =>$request->IngControlleSite,

                                                 ];
                                              if ($request->bureauSel == 'new'){                                                
                                                 
                                                $data['bureau_etudT??l'] = $request->bureau_etudT??l;
                                                $data['bureau_etud'] = $request->bureau_etud;
                                                $data['bureau_etudFax'] = $request->bureau_etudFax;  
                                                $data['bureau_etudAdr'] = $request->bureau_etudAdr;       
                                                $data['IngNumeroagrement'] = $request->IngNumeroagrement; 
                                                $data['IngControlleSite'] = $request->IngControlleSite; 
                                                  
                                              } else {
                                                
                                                $data['bureau_etud']=$request->bureauSel;

                                                  $bureau=B_bcommande::select('bureau_etud','bureau_etudAdr','bureau_etudT??l','bureau_etudFax','IngNumeroagrement','IngControlleSite')
                                                ->where('bureau_etud','=',$request->bureauSel)
                                                //->groupBy('bureau_etud','bureau_etudAdr','bureau_etudT??l','bureau_etudFax')
                                                ->first();
                                                
                                               
                                                   $data['bureau_etudT??l'] = $bureau->bureau_etudT??l;
                                                $data['bureau_etudFax'] = $bureau->bureau_etudFax;  
                                                $data['bureau_etudAdr'] = $bureau->bureau_etudAdr;  
                                                $data['IngNumeroagrement']=$bureau->IngNumeroagrement; 
                                                $data['IngControlleSite']=$bureau->IngControlleSite; 
                                                
                                                //$data['bureau_etudT??l'] = null;
                                               // $data['bureau_etudAdr'] = null;
                                                
                                               // $data['bureau_etudFax'] = null;
                                              }
                                                
                                              $user = Auth::user(); 
    
                                            // $request->DateDebuttravaux=date("m-d-Y", strtotime($request->DateDebuttravaux));
     
                                             $go= DB::table('b_bcommandes')->insertGetId($data);
                                                                                          $rows = $request->input('addmore');
                                                                                                 
                                                                                          foreach ($rows as $row)
                                                                                          {
                                                                                              $aa[] = [
                                                                                                  'bcommande_code' => $go,
                                                                                                  'D??signation' => $row['D??signation'],
                                                                                                  'NombredEtages' => $row['NombredEtages'],
                                                                                                  'EmpriseduBatiment' => $row['EmpriseduBatiment'],
                                                                                                  'NombredeBatiment' => $row['NombredeBatiment'],
                                                                                              ];
                                                                                          }
       
                                                                                          BoncommandeBloc::insert($aa);

                                                                                          //envoyer notification to admin
                                                                                          $userr  = User::where('id','=','1')->get(); 
                                                                                          $admins  = Buser::where('role_id','=','1')->get();

                                                                                         //Notification::send($userr,new NouveauxBonDeCommande($userr));

                                                                                      //  Buser::where('role_id','=','1')->firstOrFail()->notify(new NouveauxBonDeCommande($user));

                                                                                       // User:: where('id','=',$request->user()->id)->firstOrFail()->notify(new NouveauxBonDeCommande($user));

                                                                                        return Redirect::to('/list_bbcommandeClient');
    
                                                               }

                                                               /******************charger document scann??e*********************** */
                                                               public function chargerdocument(Request $request,$code){
                                                                $use=   DB::table('b_bcommandes')->where('code',$code)->first();

                                                                return view('front.compte.chargerdocumentScannee', compact('use'));
                                                                 
                                                                 
                                                               }
   /******************charger document scann??e sign??e*********************** */
  
    public  function uploadsigne(Request $request,$code){
     
      $request->validate([
           'docSig'   => 'required|file|mimes:pdf,docx,doc'
       
      ]);
     
                   $use=B_bcommande::find($code);
       
                if ($request->hasFile('docSig')) {
               
                   $data = $request->input('docSig');
                  $photo = $request->file('docSig')->getClientOriginalName();
                   $fileName = pathinfo($photo,PATHINFO_FILENAME);
                  $destination = base_path() . '/public/documentsigne';
                    $request->file('docSig')->move($destination, $photo);
                 
                   $use->docSig=$fileName;
                  
              }
              $use->docSig=$fileName;
          $use->save();
        
          return Redirect::to('/list_bbcommandeClient');
        
            }

            //*************************documentsigneEnvoyer******************************* ***/
            public function documentsigneEnvoyer(Request $request,$code){
                         return  $use=B_bcommande::find($code);      
                          }                              
}
