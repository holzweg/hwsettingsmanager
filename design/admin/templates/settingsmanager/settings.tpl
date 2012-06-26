{ezcss('http://cdn.sencha.io/ext-4.1.0-gpl/resources/css/ext-all-scoped.css')}
{ezcss('settingsmanager.css')}
{ezscript_require(array( 'ezjsc::jquery') )}
{ezscript('http://cdn.sencha.io/ext-4.1.0-gpl/ext-all.js')}
<script type="text/javascript">{literal}
    var hwsm_SelectedIniFile = "{/literal}{$ini_file}{literal}";
{/literal}</script>
{ezscript('settingsmanager.js')}
<div class="box-header">
    <div class="button-left">
        <h2 class="context-title">Settings Manager</h2>
    </div>
    <div class="float-break"></div>
</div>

<div class="box-content">

    <div id="settingsmanager-wrapper" class="content-navigation-childlist">
        <form action="/settingsmanager/settings" method="POST">
            <div class="block">
                <label for="selectedINIFile">{'Select ini file to view'|i18n('design/admin/settings')}:&nbsp;</label>
                <select id="selectedINIFile" name="selectedINIFile">
                    {section var=Files loop=$ini_files}
                        {if eq( $Files.item, $ini_file )}
                            <option value="{$Files.item}" selected="selected">{$Files.item}</option>
                        {else}
                            <option value="{$Files.item}">{$Files.item}</option>
                        {/if}
                    {/section}
                </select>
                <input type="submit" value="Select" />
            </div>
        </form>

        <div id="settingsmanager-container"></div>
    </div>
</div>
{undef}