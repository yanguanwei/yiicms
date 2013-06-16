<?php

class ModelTag
{
    public static function deleteByTag($tid)
    {
        $tid = intval($tid);
        $sql = "DELETE FROM {{model_tag}} WHERE tid='{$tid}'";
        return Yii::app()->db->createCommand($sql)->execute();
    }

    public static function delete($model_name, $ids)
    {
        $ids = (array) $ids;

        return Yii::app()->db->createCommand(
            "DELETE FROM {{model_tag}} WHERE model_name='{$model_name}' AND id IN('" . implode("', '", $ids) . "')"
        )->execute();
    }

    public static function update($model_name, $id, $tags)
    {
        $id = intval($id);
        $db = Yii::app()->db;
        $db->createCommand("DELETE FROM {{model_tag}} WHERE model_name='{$model_name}' AND id='{$id}'")->execute();

        if ($tags) {
            $sql = "INSERT INTO {{model_tag}} (id, tid, model_name, type_name) VALUES";
            $values = array();
            foreach ($tags as $type_name => $tid) {
                $tid = intval($tid);
                if ($tid) {
                    $values[] = "({$id}, {$tid}, '{$model_name}', '{$type_name}')";
                }
            }
            $sql .= implode(', ', $values);

            return $db->createCommand($sql)->execute();
        }
    }

    public static function find($model_name, $id)
    {
        $id = intval($id);
        $tags = array();
        $sql = "SELECT id, tid, type_name FROM {{model_tag}} WHERE model_name='{$model_name}' AND id='{$id}'";
        foreach (Yii::app()->db->createCommand($sql)->queryAll() as $row) {
            $tags[$row['type_name']] = $row['tid'];
        }
        return $tags;
    }
}