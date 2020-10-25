<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use DiDom\Document;

class GroupsController extends Controller
{
    protected $url = "http://dgp.cnpq.br/dgp/espelhogrupo/";
    private $array = ['error'=>'', 'result'=>[]];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = Group::all();
        foreach($groups as $group){
            $this->array['result'][] = $group;
        }
        return $this->array;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $url = $request->input('url');

        // Remove all illegal characters from a url
        $url = filter_var($url, FILTER_SANITIZE_URL);

        // Validate url
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            //URL Válida
            $scrap = $this->scrap($url);
            if($this->array['error'] === ''){
                $g = Group::updateOrCreate(
                    [
                        'espelho' => $scrap['espelho']
                    ],
                    [
                        'status' => $scrap['status'],
                        'anoformacao' => $scrap['anoformacao'],
                        'datasituacao' => $scrap['datasituacao'],
                        'ultimoenvio' => $scrap['ultimoenvio'],
                        'area' => $scrap['area'],
                        'uf' => $scrap['uf'],
                        'telefone' => $scrap['telefone'],
                        'contato' => $scrap['contato'],
                        'titulo' => $scrap['titulo'],
                        'lideres' => $scrap['lideres']
                    ]
                );
            }

        } else {
            $this->array['error'][] = "URL invalida";
        }

        $this->array['result']['srcap'] = $scrap;


        return $this->array;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function scrap($espelho)
	{


		$mapa = Array(
			0 => "status",
			1 => "anoformacao",
			2 => "datasituacao",
			3 => "ultimoenvio",
			5 => "area",
			6 => "instituicao",
			14 => "uf",
			20 => "telefone",
			22 => "contato",
			25 => "titulo"
		);

        $dom = new Document($espelho, true); //Didom

        $grupo = Array();
		$grupo['espelho'] = $espelho;
		$grupo['instituicao'] = "Procurando...";
		
		//Varre o array $mapa e cria uma variavel pra cada item
		foreach($mapa as $k => $v){
			if($dom->has('div.control-group .controls'))
				$grupo[$v] = trim($dom->find('div.control-group .controls')[$k]->text());
		}
		//Trecho específico para os lideres
		if($dom->has('div.control-group .controls')){
			$lideres = trim($dom->find('div.control-group .controls')[4]->text());
		
			$lideres = str_replace('Permite enviar email', ' ', $lideres);
			$lideres = str_replace('ui-button', ' ', $lideres);
			$lideres = preg_replace('/\$\(function\S+\;/', ' ', $lideres);
			$lideres = preg_replace('/PrimeFaces\S+\;/', ' ', $lideres);
			while(stripos($lideres, '   '))
				$lideres = str_replace('   ', '  ', $lideres);
			$lideres = explode('  ', $lideres);
			
			$lideres = array_filter($lideres, function($string){
				return (strlen(trim($string)) > 0 ? true : false);
			});

			foreach($lideres as $l) $grupo['lideres'][] = trim($l);
			$grupo['lideres'] = implode(", ", $grupo['lideres']);
		}
		
		if(strstr($grupo['instituicao'], "UNILAB")){
			return $grupo;
		}else{
			return $this->array['error'] = "Esse grupo parece não ser da Unilab " . $grupo['instituicao'];
		}
		
    }
    
    private function filtrar($string) 
	{ 
		return (strlen(trim($string)) > 0 ? true : false);
    }
    
    public function didom()
    {
        $dom = new Document('http://dgp.cnpq.br/dgp/espelhogrupo/3445622015698302', true); //Didom
        print_r($this->scrap('http://dgp.cnpq.br/dgp/espelhogrupo/3445622015698302'));   
    }
}
