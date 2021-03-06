<?php

/**
 *
 * @author Ivan Teleshun <teleshun.ivan@gmail.com>
 * @link http://molotoksoftware.com/
 * @copyright 2016 MolotokSoftware
 * @license GNU General Public License, version 3
 */

/**
 * 
 * This file is part of MolotokSoftware.
 *
 * MolotokSoftware is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * MolotokSoftware is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with MolotokSoftware.  If not, see <http://www.gnu.org/licenses/>.
 */

if (empty($items)) {
    echo CHtml::tag('p', array(), 'Отзывы отсутствуют');

} else {
    $webUser = Getter::webUser();

?>
<h3>Отзывы <small> <?=($route=='from_me')?'от меня':'обо мне'?> (<?= $count; ?>)</small></h3>

<table class="table table-hover t_reviews" width="100%">
    <thead>
    <tr>
        <th width="5%">
        </th>
        <th width="50%"><strong>Отзывы</strong>
        </th>
        <th width="25%"><strong>От</strong>
        </th>
        <th width="20%"><strong>Дата</strong>
        </th>
    </tr>
    </thead>
<?php foreach ($items as $item): ?>

<tr>
    <td>
        <img style="width:20px;" src="/img/rev<?=($item->value==5)?'up':'down'?>.png">
     
    </td>
    <td>
        <?= $item->text; ?>
        <div style="position:relative">
             <a class="auction-link" href="<?= Yii::app()->createUrl('/auction/view', array('id' => $item->entity->auction_id)); ?>"><?= $item->entity->name; ?></a>
        </div>
    </td>
    <td><?= $item->role==2?'Покупатель:':'Продавец:'; ?>

            <?php $this->widget(
        'frontend.widgets.user.UserInfo',
        ['userModel' => $item->userFrom, 'scope' => UserInfo::SCOPE_USER_SIMPLE]
    );
    ?>

        <br>
        <?php if (!empty($item->sale->price)): ?>
                <span class="span_cost">
                <?=PriceHelper::formate($item->sale->price);?>
                </span>
        <?php endif;?>
    </td>
    <td>
        <?= Yii::app()->dateFormatter->format("HH:mm, d MMMM y", strtotime($item->date)); ?>
    </td>

</tr>

<?php endforeach; 

} // end else 
?>
</table>

<div class="text-right">
            <?$this->widget('CLinkPager', array(
                'pages' => $pages,
                'maxButtonCount' => 5,
                'firstPageLabel' => 'в начало',
                'lastPageLabel' => 'в конец',
                'selectedPageCssClass' => 'active',
                'prevPageLabel' => '&lt; ',
                'nextPageLabel' => ' &gt;',
                'header' => '',
                'footer' => '',
                'cssFile' => false,
                'htmlOptions' => ['class' => 'pagination'],
                'selectedPageCssClass' => 'active',
            ))?>
</div>