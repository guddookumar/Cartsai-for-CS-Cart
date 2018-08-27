{* $Id: cc_multisafepay.tpl,v 1.0 2008/04/20 letun Exp $ *}
{assign var="r_url" value="`$config.http_location`/`$config.customer_index`?dispatch=payment_notification.notify&payment=cartasi"}
{assign var="e_url" value="`$config.http_location`/`$config.customer_index`?dispatch=payment_notification&payment_notification.result=cartasi"}
<h3>CartaSi</h3>
<p />

{* Test/Live mode *}
<div class="form-field">
    <label for="mode">Type account:</label>
    <select name="payment_data[processor_params][mode]" id="mode">
        <option value="P" {if $processor_params.mode == "P"}selected="selected"{/if}>Live account</option>
        <option value="T" {if $processor_params.mode == "T"}selected="selected"{/if}>Test account</option>
    </select>
</div>


{* mac *}
<div class="form-field">
    <label for="mac">CartaSi MAC</label>
    <input type="text" name="payment_data[processor_params][mac]" id="mac" value="{$processor_params.mac|escape}" class="input-text" />
</div>

{* alias *}
<div class="form-field">
    <label for="alias">CartaSi Alias</label>
    <input type="text" name="payment_data[processor_params][alias]" maxlength="20" id="alias" value="{$processor_params.alias|escape}" class="input-text" />
</div>

{* Notificatie URL *}
<div class="form-field">
    <label for="notify_url">Notificatie URL:</label>
    {$r_url}
    <input type="hidden" name="payment_data[processor_params][notify_url]" id="securitycode" value="{$r_url|escape}"/>
</div>

{assign var="liveurl" value="https://int-ecommerce.cartasi.it/ecomm/ecomm/DispatcherServlet"}
{assign var="productionurl" value="https://ecommerce.cartasi.it/ecomm/ecomm/DispatcherServlet"}

<!--
https://coll-ecommerce.keyclient.it/ecomm/ecomm/DispatcherServlet
https://ecommerce.keyclient.it/ecomm/ecomm/DispatcherServlet
-->

<input type="hidden" name="payment_data[processor_params][liveurl]" id="liveurl" value="{$liveurl|escape}"/>
<input type="hidden" name="payment_data[processor_params][productionurl]" id="productionurl" value="{$productionurl|escape}"/>