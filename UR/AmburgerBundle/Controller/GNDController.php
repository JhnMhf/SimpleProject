<?php

namespace UR\AmburgerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

//https://packagist.org/packages/eightpoints/guzzle-bundle
//http://docs.guzzlephp.org/en/latest/
class GNDController extends Controller
{
    private $LOGGER;
    
    //@TODO: Finish and use GNDController/ GND Logic?
    
    public function getLogger(){
        if($this->LOGGER == null){
            $this->LOGGER = $this->LOGGER = $this->get('monolog.logger.personMerging');
        }
        
        return $this->LOGGER;
    }
    
    
    public function subjectAction($name)
    {
        $url = "/subject?name=".$name;
        
        $response = $this->request($url);
        
        $responseBodyJson = $response->getBody();
        
        $serializer = $this->get('serializer');
        $responseBody = $serializer->deserialize($responseBodyJson, 'array', 'json');
        
        
        //$responseBody = json_decode($responseBodyJson, true);
        
        $preferredNames = array();

        
        foreach($responseBody as $element){
            if(isset($element["@graph"])){
                if(isset($element["@graph"][0]["@type"])) {
                    $types = $element["@graph"][0]["@type"];
  
                    $geographicNameIdentifierFound = false;
                    $territoryOrAdministrativeUnitIdenitifierFound = false;
                    if(is_array($types)){
                        foreach($types as $type){
                            if($type == "http://d-nb.info/standards/elementset/gnd#PlaceOrGeographicName"){
                                $geographicNameIdentifierFound = true;
                                continue;
                            }
                            
                            if($type == "http://d-nb.info/standards/elementset/gnd#TerritorialCorporateBodyOrAdministrativeUnit"){
                                $territoryOrAdministrativeUnitIdenitifierFound = true;
                            }
                        }
                    }else{
                        // not necessary to check here?
                    }

                    
                    if($geographicNameIdentifierFound && $territoryOrAdministrativeUnitIdenitifierFound){
                        
                        if(isset($element["@graph"][0]["preferredNameForThePlaceOrGeographicName"])){
                            if(is_array($element["@graph"][0]["preferredNameForThePlaceOrGeographicName"])){
                                foreach($element["@graph"][0]["preferredNameForThePlaceOrGeographicName"] as $name){
                                    $name = addslashes(trim($name));
                                    if(!in_array($name, $preferredNames)){
                                        $preferredNames[] = $name;
                                    }
                                    
                                }
                                
                            } else {
                                $this->getLogger()->debug("Possible preferred Names: ".$element["@graph"][0]["preferredNameForThePlaceOrGeographicName"]);
                                $singleName = addslashes(trim($element["@graph"][0]["preferredNameForThePlaceOrGeographicName"]));
                                if(!in_array($singleName, $preferredNames)){
                                    $preferredNames[] = $singleName;
                                }
                            }
                        }
                    }
                }
            }
            
            
        }
        //Lipsk
        $jsonResponse = new JsonResponse();
        $jsonResponse->setContent($serializer->serialize($preferredNames, 'json'));
        
        return $jsonResponse;
    }
    
    public function personAction($name)
    {
        
    }

    public function searchAction($name)
    {
        
    }
    
    private function request($url){
        $client   = $this->get('guzzle.client.gnd_api');
        return $client->get($url);
    }
}
