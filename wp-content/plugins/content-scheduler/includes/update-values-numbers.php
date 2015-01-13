<?php
/*
    Update a number of options values from english strings / slugs to numbers
*/
switch( $this->options['exp-status'] )
{
    case 'Hold':
        $this->options['exp-status'] = '0';
        break;
    case 'Delete':
        $this->options['exp-status'] = '2';
        break;
    default:
        $this->options['exp-status'] = '1';
} // end switch
switch( $this->options['chg-status'] )
{
    case 'No Change':
        $this->options['chg-status'] = '0';
        break;
    case 'Pending':
        $this->options['chg-status'] = '1';
        break;
    case 'Private':
        $this->options['chg-status'] = '3';
        break;
    default:
        $this->options['chg-status'] = '2';
}
/*
$r = (1 == $v) ? 'Yes' : 'No'; // $r is set to 'Yes'
$r = (3 == $v) ? 'Yes' : 'No'; // $r is set to 'No'
*/
$this->options['chg-sticky'] = ( 'No Change' == $this->options['chg-sticky'] ) ? '0' : '1';
switch( $this->options['chg-cat-method'] )
{
    case 'Add selected':
        $this->options['chg-cat-method'] = '1';
        break;
    case 'Remove selected':
        $this->options['chg-cat-method'] = '2';
        break;
    case 'Match selected':
        $this->options['chg-cat-method'] = '3';
        break;
    default:
        $this->options['chg-cat-method'] = '0';
}
$this->options['notify-on'] = ( 'Notification off' == $this->options['notify-on'] ) ? '0' : '1';
$this->options['notify-admin'] = ( 'Do not notify admin' == $this->options['notify-admin'] ) ? '0' : '1';
$this->options['notify-author'] = ( 'Do not notify author' == $this->options['notify-author'] ) ? '0' : '1';
$this->options['notify-expire'] = ( 'Do not notify on expiration' == $this->options['notify-expire'] ) ? '0' : '1';
$this->options['show-columns'] = ( 'Do not show expiration in columns' == $this->options['show-columns'] ) ? '0' : '1';
$this->options['datepicker'] = ( 'Do not use datepicker' == $this->options['datepicker'] ) ? '0' : '1';
$this->options['remove-cs-data'] = ( 'Do not remove data' == $this->options['remove-cs-data'] ) ? '0' : '1';
?>