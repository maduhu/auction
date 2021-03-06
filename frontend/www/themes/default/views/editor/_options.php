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


$i=0;
echo '<div class="cat-list-block-wrp clearfix">';


foreach ($options as $option) {

	if (count($options)>3) {
		if (($i%3)==0 && $i!=0) {

			echo '</div>';
			echo '<div class="cat-list-block-wrp clearfix">';
		}
	}


	echo "<div class='cat-list-block'>";

	switch ($option['type']) {

		case Attribute::TYPE_DROPDOWN:
			$i++;
			if ($option['mandatory'] == 1) {echo "<div class='mandat'>"; $option['name'] = '* '.$option['name'];} else {echo "<div>";}
			echo CHtml::label($option['name'], 'options_' . $option['attribute_id']);
			echo "</div>";
			$selected_id = (isset($selected) && $selected) ? $option['value_id'] : '';
			echo CHtml::dropDownList('options[0][' . $option['attribute_id'] . ']', $selected_id, getAttributeValues($option['attribute_id']), array('empty' => Yii::t('basic', '- select value -')));
			echo '<p class="m_type_1"></p>';
            break;
		case Attribute::TYPE_RADIO_LIST:
			$i++;
			if ($option['mandatory'] == 1) {echo "<div class='checkbox-list mandat'>"; $option['name'] = '* '.$option['name'];} else {echo "<div class='checkbox-list'>";}
			echo CHtml::label($option['name'], 'options_' . $option['attribute_id']);
			$values = getAttributeValues($option['attribute_id']);
			$default_value = '';
			if (!empty($values)) {
				$tmp = array_keys($values);
				$default_value = array_shift($tmp);
			}
			$selected_id = (isset($selected) && $selected) ? $option['value_id'] : $default_value;
			echo CHtml::radioButtonList('options[0][' . $option['attribute_id'] . ']', $selected_id, $values);
			echo "</div>";
            echo '<p class="m_type_3"></p>';
			break;

		case Attribute::TYPE_CHECKBOX_LIST:
			$i++;
			if ($option['mandatory'] == 1) {echo "<div class='checkbox-list mandat'>"; $option['name'] = '* '.$option['name'];} else {echo "<div class='checkbox-list'>";}
			echo CHtml::label($option['name'], 'options_' . $option['attribute_id']);
			$values = getAttributeValues($option['attribute_id']);
			$selected_values = (isset($selected) && $selected) ? getSelectedValues($option['attribute_id'], $option['auction_id']) : array();
			echo CHtml::checkBoxList('options[0][' . $option['attribute_id'] . ']', $selected_values, $values);
			echo "</div>";
            echo '<p class="m_type_4"></p>';
			break;

		case Attribute::TYPE_TEXT:
			$i++;
            if ($option['mandatory'] == 1) {echo "<div class='mandat'>"; $option['name'] = '* '.$option['name'];} else {echo "<div>";}
			echo CHtml::label($option['name'], 'options_' . $option['attribute_id']);
			$value = (isset($selected) && $selected) ? $option['value'] : '';
			echo CHtml::telField('options[1][' . $option['attribute_id'] . ']', $value);
			echo "</div>";
            echo '<p class="m_type_6"></p>';
			break;

		case Attribute::TYPE_TEXT_AREA:
			$i++;
            if ($option['mandatory'] == 1) {echo "<div class='mandat'>"; $option['name'] = '* '.$option['name'];} else {echo "<div>";}
			echo CHtml::label($option['name'], 'options_' . $option['attribute_id'], array('class' => ''));
			$value = (isset($selected) && $selected) ? $option['value'] : '';
			echo CHtml::textArea('options[1][' . $option['attribute_id'] . ']', $value, array('class' => ''));
            echo "</div>";
            echo '<p class="m_type_7"></p>';
			break;
            
        case Attribute::TYPE_TEXT_RANGE:
			$i++;
            if ($option['mandatory'] == 1) {echo "<div class='mandat'>"; $option['name'] = '* '.$option['name'];} else {echo "<div>";}
			echo CHtml::label($option['name'], 'options_' . $option['attribute_id']);
			$value = (isset($selected) && $selected) ? $option['value'] : '';
            echo CHtml::telField('options[1][' . $option['attribute_id'] . ']', $value, array('onkeyUp'=>'return type_text_range_check(this);', 'placeholder' => Yii::t('basic', 'Enter an integer'), 'style' => 'padding-left: 5px;'));
			echo "</div>";
            echo '<p class="m_type_10"></p>';
			break;
            
		case Attribute::TYPE_DEPENDET_SELECT:
			$i+=2;
			$selected_id = (isset($selected) && $selected) ? $option['value_id'] : '';
			$rootAttr = Attribute::model()->findByPk($option['attribute_id']);
			$childAttr = Attribute::model()->findByPk($rootAttr->child_id);
			if (is_null($childAttr)) {
				echo CHtml::tag('p', Yii::t('basic', 'Child element is not defined'));
				break;
			}

			$childAttrName = 'options_0_' . $childAttr->attribute_id;
			
            if ($option['mandatory'] == 1) {echo "<div class='mandat'>"; $option['name'] = '* '.$option['name'];} else {echo "<div>";}
            
			echo CHtml::label(
				$option['name'],
				'options_0_' . $option['attribute_id'],
				array('class' => 'control-label')
			);
			echo "</div>";

			echo Chtml::dropDownList(
				'options[0][' . $option['attribute_id'] . ']',
				$selected_id,
				getAttributeValues($option['attribute_id']),
				array(
					'class' => 'span8',
					'empty' => Yii::t('basic', '- select value -'),
					'onchange' => new CJavaScriptExpression('
var select_id = $("#' . 'options_0_' . $option['attribute_id'] . '").find("option:selected").val();

$.ajax({

url: "' . Yii::app()->createUrl('/creator/getChildValues') . '",

data: {"id":select_id},

type: "GET",

dataType: "json",

beforeSend: function() {

$("#' . $childAttrName . '").val("");

$("#' . $childAttrName . '").find("option:selected").html("'.Yii::t('basic', 'Loading...').'");

$("#' . $childAttrName . '").attr("disabled", true);

},

success: function(data) {
$("#' . $childAttrName . '").html(data.options);
$("#' . $childAttrName . '").attr("disabled", false);
}
});
')));
echo '<p class="m_type_9"></p>';
			echo "</div>";
			echo "<div class='cat-list-block'>";
			if ($childAttr->mandatory == 1) {echo "<div class='mandat m_type_9'>"; $childAttr->name = '* '.$childAttr->name;} else {echo "<div>";}

			echo CHtml::label(
				$childAttr->name,
				'options_0_' . $childAttr->attribute_id,
				array('class' => 'control-label')
			);
			echo "</div>";

			$selected_child_id = '';

			if (isset($selected) && $selected) {
				$aav = Yii::app()->db->createCommand()
					->select('*')
					->from('auction_attribute_value')
					->where(
						'auction_id=:auction_id and attribute_id=:attribute_id',
						array(
							':auction_id' => $auction_id,
							':attribute_id' => $childAttr->attribute_id
						)
					)
					->queryRow();

				if ($aav !== false) {
					$selected_child_id = $aav['value_id'];
				}
			}

			$depValues = getDependentAttrValues($selected_id);

			echo CHtml::dropDownList(
				'options[0][' . $childAttr->attribute_id . ']',
				$selected_child_id,
				$depValues,
				array(
					'class' => 'span8',
					'empty' => Yii::t('basic', '- select value -')
				)
			);

            echo '<p class="m_type_9"></p>';
			break;
	}
	echo "</div>";


}
echo '</div>';


function getDependentAttrValues($rootAttrId)
{
	$aav = AttributeValues::model()->findAll('parent_id=:parent_id', array(
		':parent_id' => $rootAttrId
	));
	return CHtml::listData($aav, 'value_id', 'value');
}


function getAttributeValues($attr_id)
{

	$data = array();
	$values = Yii::app()->db->createCommand()
		->select('value, value_id')
		->from('attribute_values')
		->where('attribute_id=:attribute_id', array(':attribute_id' => $attr_id))
		->order('sort ASC')
		->queryAll();


	foreach ($values as $value) {
		$data[$value['value_id']] = $value['value'];
	}
	return $data;
}


function getSelectedValues($attr_id, $auction_id)
{

	$values = Yii::app()->db->createCommand()
		->select('value_id')
		->from('auction_attribute_value')
		->where('attribute_id=:attribute_id and auction_id=:auction_id', array(':attribute_id' => $attr_id,
			':auction_id' => $auction_id
		))
		->queryColumn();

	return $values;

}

