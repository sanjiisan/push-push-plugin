<?php
/**
 * Plugin Name: Push Push Go
 * Plugin URI: https://pushpushgo.com/
 * Description: Simply integration with PushPushGo service
 * Version: 1.0
 * Author: Hunger Programmers
 * Author URI: http://hunger-programmers.com/
 * License: GNU v3.0
 */

define('PUSHPUSHGO_VERSION', '1.0');
define('PUSHPUSHGO_API_ADDRESS', 'https://api.pushpushgo.com');
define('PUSHPUSHGO_APPLICATION_ADDRESS', 'https://app.pushpushgo.com');
define('PUSHPUSHGO_TUTORIAL_ADDRESS', 'https://pushpushgo.com');

add_action('admin_menu', 'puhspush_menu');
function puhspush_menu()
{
    add_menu_page('PushPushGo Settings', 'PushPushGo', 'administrator', 'pushpushgo-settings', 'pushpush_settings_page', plugin_dir_url(__FILE__) . 'assets/ppg-wp-icon.png');
}

function pushpush_settings_page()
{
    ?>

    <div class="wrap" id="pushpushgo">
        <h1><?php echo __('Integracja z PushPush Go'); ?></h1>

        <p class="text">
            <?php echo __('PushPushGo - Aplikacja służąca do wysyłania powiadomień web push, aby przeprowadzić integrację należy posiadać konto w serwisie <a href="' . PUSHPUSHGO_APPLICATION_ADDRESS . '">PushPushGO!</a>. <br />Po zalogowaniu się do aplikacji przejdź do sekcji Profil i wygeneruj Kod API na samym dole strony, następnie wklej go w poniższym polu i pobierz projekty. <br />Szczegółowa instrukcja integracji znajduje się <a href="' . PUSHPUSHGO_TUTORIAL_ADDRESS . '">TUTAJ</a>'); ?>
        </p>

        <div id="statement" class="notice is-dismissible" style="display: none;">
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text">Dismiss this notice.</span>
            </button>
        </div>

        <form method="post" action="options.php">
            <?php settings_fields('settings-group'); ?>
            <?php do_settings_sections('settings-group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php echo __('Twój kod integracyjny'); ?></th>
                    <td>
                        <label for="integration_code">
                            <input type="text" name="integration_code" id="integration_code"
                                   onchange="clearSavedValues()"
                                   placeholder="<?php echo __('Kod integracyjny'); ?>"
                                   value="<?php echo esc_attr(get_option('integration_code')); ?>"/>
                        </label>
                    </td>
                </tr>
            </table>

            <input type="hidden" name="project_embed_id" id="project_embed_id"
                   value="<?php echo esc_attr(get_option('project_embed_id')); ?>"/>

            <table class="wp-list-table widefat fixed striped" style="display: none;" id="projects-table">
                <thead>
                <tr>
                    <td scope="col" class="manage-column"><?php echo __('Projekt'); ?></td>
                    <th scope="col" class="manage-column"><?php echo __('Zintegrowany'); ?></th>
                    <th scope="col" class="manage-column"><?php echo __('Integruj'); ?></th>
                </tr>
                </thead>
                <tbody id="projects-table-body"></tbody>
                <tfoot>
                <tr>
                    <td scope="col"
                        class="manage-column column-primary sorted desc"><?php echo __('Projekt'); ?></td>
                    <th scope="col" class="manage-column "><?php echo __('Zintegrowany'); ?></th>
                    <th scope="col" class="manage-column"><?php echo __('Integruj'); ?></th>
                </tr>
                </tfoot>
            </table>

            <?php submit_button(__('Zapisz')); ?>
        </form>
    </div>
    <script type="application/javascript">
        var
            $ = jQuery,
            integrationCode = document.getElementById('integration_code').value,
            embedId = document.getElementById('project_embed_id').value;

        if (integrationCode != '') {
            $
                .ajax({
                    url: "<?php echo PUSHPUSHGO_API_ADDRESS; ?>/project",
                    headers: {
                        'X-Token': integrationCode
                    }
                })
                .success(successCallback)
                .error(errorCallback);

            function errorCallback(response) {
                renderStatement('<?php echo __('Podany kod jest nieprawidłowy'); ?> ', 'error');
            }

            function successCallback(response) {
                response.forEach(renderRow);

                function renderRow(project) {
                    var row = document.createElement('tr'),
                        addressCell = document.createElement('td'),
                        statusCell = document.createElement('td'),
                        integrationCell = document.createElement('td'),
                        table = document.getElementById('projects-table'),
                        tableBody = document.getElementById('projects-table-body'),
                        integrated = project.id == '<?php echo esc_attr(get_option('project_embed_id')); ?>';

                    addressCell.innerHTML = '<a href="' + project.siteUrl + '" target="_blank">' + project.name + '</a>';
                    statusCell.innerText = integrated ? 'TAK' : 'NIE';
                    integrationCell.innerHTML = !integrated ? '<input type="radio" name="integration" onclick="integrateProject(\'' + project.id + '\');"/>' : '';

                    row.appendChild(addressCell);
                    row.appendChild(statusCell);
                    row.appendChild(integrationCell);

                    row.className = 'iedit hentry';

                    tableBody.appendChild(row);
                    table.style.display = 'table';
                }

                //Send request to check properly integrate
                if (embedId != '') {
                    $
                        .ajax({
                            url: "<?php echo PUSHPUSHGO_API_ADDRESS; ?>/check",
                            global: false,
                            type: 'POST',
                            contentType: 'application/json',
                            context: document.body,
                            headers: {
                                'X-Token': integrationCode
                            },
                            data: '{"id": "' + embedId + '"}',
                            dataType: 'text'
                        })
                        .error(checkErrorCallback)
                        .success(checkSuccessCallback);

                    function checkSuccessCallback(data) {
                        if (data.codePresents) {
                            renderStatement('<?php echo __('Strona zintegrowana poprawnie'); ?>', 'success');
                        } else {
                            checkErrorCallback();
                        }
                    }

                    function checkErrorCallback(response) {
                        renderStatement('<?php echo __(' Integracja nie przebiegła poprawnie, sprawdź podane w projekcie adresy URL czy zgadzają się z adresem Twojej strony'); ?>', 'error');
                    }
                }

                if (response.length == 0) {
                    renderStatement('<?php echo __('Aktualnie nie masz utworzonego żadnego projektu w PushPushGO, przejdź do PushPushGO i utwórz nowy projekt.'); ?>', 'info');
                }
            }
        }

        function integrateProject(projectId) {
            document.getElementById('project_embed_id').value = projectId;
        }

        function clearSavedValues() {
            document.getElementById('project_embed_id').value = '';
        }

        function renderStatement(content, type) {
            var statement = document.getElementById('statement'),
                paragraph = document.createElement('p');

            paragraph.innerText = content;

            statement.className += ' notice-' + type;
            statement.appendChild(paragraph);
            statement.style.display = 'block';
        }
    </script>
<?php
}

add_action('admin_init', 'pushpushgo_settings');
function pushpushgo_settings()
{
    register_setting('settings-group', 'integration_code');
    register_setting('settings-group', 'project_embed_id');
}

add_action('wp_head', 'embed_integration_code');
function embed_integration_code()
{
    if ('' !== get_option('integration_code') && '' != get_option('project_embed_id')) {
        echo '<script charset="UTF-8" src="https://cdn.stppg.co/js/' . get_option('project_embed_id') . '.js" async></script>';
    }
}

add_action('admin_enqueue_scripts', 'admin_enqueue_scripts');
function admin_enqueue_scripts()
{
    wp_enqueue_style('push_style', plugin_dir_url(__FILE__) . 'assets/admin_style.css', array(), PUSHPUSHGO_VERSION);
}

