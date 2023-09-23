<?php

namespace app\core\db;
use app\core\Application;
use app\core\Model;


abstract class DbModel extends Model
{
    abstract static public function tableName(): string;

    //should retrun all db column name
    abstract public function attributes(): array;

    abstract static  public function primaryKey():string;
    public function save()
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(fn($attr) => ":$attr", $attributes);
        $stmt = self::prepare("INSERT INTO $tableName (" . implode(',', $attributes) . ")
                VALUES (" . implode(',', $params) . ")");
        foreach ($attributes as $attribute)
        {
            $stmt->bindValue(":$attribute",$this->{$attribute});
        }
        $stmt->execute();
        return true;
    }

    /**
     * Summary of findOne
     * @param mixed $where
     * @return bool|object
     */
    public static function findOne($where){ //eg: [email => ex@gmail.com etc...]

        //static::tableName() will call the functoin on which class has called it
        $tableName = static::tableName();
        $attributes = array_keys($where);

        //SELECT * FROM $tableName WHERE (corresponding to $where)
        $sql = implode("AND ",array_map(fn($attr) => "$attr = :$attr",$attributes));
        $statement = self::prepare("SELECT * FROM $tableName WHERE $sql");
        foreach($where as $key => $item){
            $statement->bindValue(":$key", $item);
        }

        $statement->execute();
        //gives instance of the class called upon
        return $statement->fetchObject(static::class);
    }
    public static function prepare($sql)
    {
        return Application::$app->db->pdo->prepare($sql);
    }
}