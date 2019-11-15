<?php

namespace diecoding\rbac\components;

use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use presensi\components\Phone;
use common\helpers\MenuHelper;

/**
 * @inheritDoc
 * 
 * @author Die Coding (Sugeng Sulistiyawan) <diecoding@gmail.com>
 * @copyright 2019 Die Coding
 * @license MIT
 * @link https://www.diecoding.com
 * @version 1.0.0
 */
class ListMenu extends Component
{
    /**
     *
     */
    public function main()
    {

        // return MenuHelper::getList();

        return ArrayHelper::merge(
            MenuHelper::getList(),
            // $this->sms(),
            $this->hakAkses()
        );
    }

    /**
     *
     */
    public function sms()
    {
        $countNewInbox = Phone::getCountNewInbox();
        $countOutbox   = Phone::getCountOutbox();
        $items['sms'] = [
            'label'   => 'SMS Manual',
            'icon'    => 'envelope-o',
            // 'visible' => Yii::$app->assign->isAssign('Administrator'),
            'badge'   => ($countNewInbox > 0 ? '<small class="label pull-right bg-green">' . $countNewInbox . '</small>' : false) . ' ' . ($countOutbox > 0 ? '<small class="label pull-right bg-red">' . $countOutbox . '</small>' : false),
            'items'   => [
                ['label' => 'Buat Pesan', 'icon' => 'pencil', 'url' => ['/phone/default/index']],
                [
                    'label' => 'Inbox',
                    'icon' => 'inbox',
                    'url' => ['/phone/inbox/index'],
                    'badge' => ($countNewInbox > 0 ? '<small class="label pull-right bg-green">' . $countNewInbox . '</small>' : false),
                ],
                ['label' => 'Sent', 'icon' => 'paper-plane-o', 'url' => ['/phone/sentitems/index']],
                [
                    'label' => 'Outbox',
                    'icon' => 'hourglass-2',
                    'url' => ['/phone/outbox/index'],
                    'badge' => ($countOutbox > 0 ? '<small class="label pull-right bg-red">' . $countOutbox . '</small>' : false),
                ],
            ]
        ];

        return $items;
    }

    /**
     *
     */
    public function hakAkses()
    {
        $visible = !Yii::$app->user->isGuest;
        // $visible = $visible && Yii::$app->assign->hasAssign('Developer');
        $visible = $visible && !Yii::$app->assign->isGuest;

        if (!$visible) {
            return [];
        }

        $items = [];
        foreach (Yii::$app->assign->listAssign as $_assign) {

            if (Yii::$app->assign->is($_assign)) {
                $classActive = "active";
                $url         = "javascript:void(0);";
                $linkOptions = [
                    "class" => $classActive,
                ];
            } else {
                $classActive = null;
                $url         = ["/site/set-assign"];
                $linkOptions = [
                    'data' => [
                        'method' => 'post',
                        'params' => [
                            'assign' => $_assign,
                        ]
                    ]
                ];
            }

            $items[] = [
                'label' => $_assign,
                'url'   => $url,
                'icon'  => 'user',
                'options' => [
                    'class' => $classActive,
                ],
                'linkOptions' => $linkOptions,
            ];
        }

        $menu[] = [
            'label'   => 'Hak Akses',
            'icon'    => 'users',
            'url'     => 'javascript:void(0);',
            'items'   => $items,
        ];

        return $menu;
    }
}
