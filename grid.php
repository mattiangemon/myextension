<?php
namespace yii\myextension\MyGridView;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\Query;
use yii\db\Command;
use yii\helpers\Html;


/******************************************************************************
 * Classe per la visualizzazione degli elementi dal database e raggruppati
 * in una tabella.
 * Questa classe è stata sviluppata per l'utilizzo all'interno di Yii2
 * 
 * @author      Mattia Leonardo Angelillo
 * @email       mattia.angelillo@gmail.com
 * @version     1.0.0
 * 
 * Esempio:
 * <?php MyGridView::render([
 *      'tables' => [
 *          'table1',
 *          'table2',
 *          ..........
 *          'tableN'
 *      ]
 *      'columns' => [
 *          'columns1',
 *          'columns2',
 *          ..........
 *          'columnsN',
 *      ],
 *      'condition => 'condizione per la query in puro SQL'
 * ]);?>
******************************************************************************/
class MyGridView{
    
    private static $db;
    
    
    

    public static function init(){
        self::$db = Yii::$app->db;
    }
    
    public static function tables(Array $config){
        if(!isset($config['tables'])){
            throw new InvalidConfigException("The tables are must be specified.");
        }elseif(count($config['tables'])==0){
            throw new InvalidConfigException("The attribute \"table\" cannot be empty.");
        }elseif(!is_array($config['tables'])){
            throw new InvalidConfigException("The attribute isn't array.");
        }
        
        return implode(",", $config['tables']);
    }
    
    public static function columns(Array $config){
        if(!isset($config['columns'])){
            throw new InvalidConfigException("The columns are must be specified.");
        }elseif(count($config['columns'])==0){
            throw new InvalidConfigException("The attribute \"columns\" cannot be empty.");
        }elseif(!is_array($config['columns'])){
            throw new InvalidConfigException("The attribute isn't array.");
        }
        
        return implode(",", $config['columns']);
    }
    
    public static function condition($config){
        if(isset($config['condition'])){
            if($config['condition']!=""){
                return $config['condition'];
            }else{
                throw new InvalidConfigException("Condition cannot be empty.");
            }
        }else return 1;
    }

    public static function render($config){
        self::init();
        
        //List
        self::listView($config);
    }
    
    public static function listView($config){
        //Set query values
        $tables     = self::tables($config);
        $columns    = self::columns($config);
        $condition  = self::condition($config);
        
        //Query
        $query = new Query;
        $query->select($columns)
                ->from($tables)
                ->where($condition);
        $command = $query->createCommand(self::$db);
        $rows = $command->queryAll();
        
        
        foreach ($rows as $key => $value){
            echo Html::beginTag("table", ['class'=>'table table-striped table-bordered detail-view']);
                foreach ($value as $key1 => $value1){
                    echo Html::beginTag("tr");
                        echo Html::beginTag("th");
                        echo ucfirst($key1);
                        echo Html::endTag("th");

                        echo Html::beginTag("td");
                        echo $value1;
                        echo Html::endTag("td");
                    echo Html::endTag("tr");
                }
            echo Html::endTag("table"); 
        }
    }
}
