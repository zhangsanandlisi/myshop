<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "mulu".
 *
 * @property int $id
 * @property string $name
 * @property string $icon
 * @property string $url
 * @property int $parent_id
 */
class Mulu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mulu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id'], 'integer'],
            [['name', 'icon', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'icon' => 'Icon',
            'url' => 'Url',
            'parent_id' => 'Parent ID',
        ];
    }

    public static function menu(){
        $menu=[
            [
                'label' => '商品管理',
                'icon' => 'shopping-cart',
                'url' => '#',
                'items' => [
                    ['label' => '商品列表', 'icon' => 'eye', 'url' => ['/goods/index'],],
                    ['label' => '添加商品', 'icon' => 'plus', 'url' => ['/goods/add'],],
                ],
            ],
        ];
        //定义空数组装菜单
        $menu=[];
         //得到一级目录
        $menus=Mulu::find()->where(['parent_id'=>0])->all();
        foreach ($menus as $menu){
          $newMenu=[];
          $newMenu['label']=$menu->name;
            $newMenu['icon']=$menu->icon;
            $newMenu['url']=$menu->url;

            //通过一级找所有二级
            $menusSon=self::find()->where(['parent_id'=>$menu->id])->all();
            foreach($menusSon as $menuSon){
                $newMenuSon=[];
                $newMenuSon['label']=$menuSon->name;
                $newMenuSon['icon']=$menuSon->icon;
                $newMenuSon['url']=$menuSon->url;
                $newMenu['items'][]=$newMenuSon;
            }

            $menuAll[]=$newMenu;
        }
        return $menuAll;
    }
}
