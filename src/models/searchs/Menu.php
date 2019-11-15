<?php

namespace diecoding\rbac\models\searchs;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use diecoding\rbac\models\Menu as MenuModel;

/**
 * Menu represents the model behind the search form of `diecoding\rbac\models\Menu`.
 */
class Menu extends MenuModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'parent', 'order'], 'integer'],
            [['name', 'route', 'assign', 'visible', 'icon', 'options'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = MenuModel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'parent' => $this->parent,
            'order' => $this->order,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'route', $this->route])
            ->andFilterWhere(['like', 'assign', $this->assign])
            ->andFilterWhere(['like', 'visible', $this->visible])
            ->andFilterWhere(['like', 'icon', $this->icon])
            ->andFilterWhere(['like', 'options', $this->options]);

        return $dataProvider;
    }
}
