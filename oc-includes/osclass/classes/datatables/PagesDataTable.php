<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

    /**
     * PagesDataTable class
     * 
     * @since 3.1
     * @package Osclass
     * @subpackage classes
     * @author Osclass
     */
    class PagesDataTable extends DataTable
    {

        private $pages;
        
        public function table($params)
        {
            
            $this->addTableHeader();

            $start = ((int)$params['iPage']-1) * $params['iDisplayLength'];

            $this->start = intval( $start );
            $this->limit = intval( $params['iDisplayLength'] );
            
            $pages = Page::newInstance()->listAll(0, null, null, $this->start, $this->limit);
            $this->processData($pages);
            
            $this->total = Page::newInstance()->count(0);
            $this->total_filtered = $this->total;
            
            return $this->getData();
        }

        private function addTableHeader()
        {

            if(osc_get_preference('admin_theme') == 'modern') {
                $this->addColumn('bulkactions', '<input id="check_all" type="checkbox" />');
            } else {
                $this->addColumn('bulkactions', '<div class="form-check">
                            <label class="form-check-label">
                                <input id="check_all" class="form-check-input" type="checkbox" />
                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>');
            }

            $this->addColumn('internal_name', __('Internal name'));
            $this->addColumn('title', __('Title'));
            $this->addColumn('order', __('Order'));

            if(osc_get_preference('admin_theme') == 'evolution') {
                $this->addColumn('actions', __('Actions'));
            }

            $dummy = &$this;
            osc_run_hook("admin_pages_table", $dummy);
        }
        
        private function processData($pages)
        {
            if(!empty($pages)) {
            
                $prefLocale = osc_current_user_locale();
                foreach($pages as $aRow) {
                    $row     = array();
                    $content = array();

                    if( isset($aRow['locale'][$prefLocale]) && !empty($aRow['locale'][$prefLocale]['s_title']) ) {
                        $content = $aRow['locale'][$prefLocale];
                    } else {
                        $content = current($aRow['locale']);
                    }

                    // -- options --
                    $options   = array();
                    View::newInstance()->_exportVariableToView('page', $aRow );

                    if(osc_get_preference('admin_theme') == 'modern') {
                        $options[] = '<a href="' . osc_static_page_url() . '" target="_blank">' . __('View page') . '</a>';
                        $options[] = '<a href="' . osc_admin_base_url(true) . '?page=pages&amp;action=edit&amp;id=' . $aRow['pk_i_id'] . '">' . __('Edit') . '</a>';
                        if( !$aRow['b_indelible'] ) {
                            $options[] = '<a onclick="return delete_dialog(\'' . $aRow['pk_i_id'] . '\');" href="' . osc_admin_base_url(true) . '?page=pages&amp;action=delete&amp;id=' . $aRow['pk_i_id'] . '&amp;' . osc_csrf_token_url() . '">' . __('Delete') . '</a>';
                        }

                        $auxOptions = '<ul>'.PHP_EOL;
                        foreach( $options as $actual ) {
                            $auxOptions .= '<li>'.$actual.'</li>'.PHP_EOL;
                        }
                        $actions = '<div class="actions">'.$auxOptions.'</div>'.PHP_EOL;

                        $row['bulkactions'] = '<input type="checkbox" name="id[]"" value="' . $aRow['pk_i_id'] . '"" />';
                        $row['internal_name'] = $aRow['s_internal_name'] . $actions;
                        $row['title'] = $content['s_title'];
                        $row['order'] = '<div class="order-box">' . $aRow['i_order'] . ' <img class="up" onclick="order_up(' . $aRow['pk_i_id'] . ');" src="' . osc_current_admin_theme_url('images/arrow_up.png') . '" alt="' . __('Up') . '" title="' . __('Up') . '" />  <img class="down" onclick="order_down(' . $aRow['pk_i_id'] . ');" src="' . osc_current_admin_theme_url('images/arrow_down.png') .'" alt="' . __('Down') . '" title="' . __('Down') . '" /></div>';
                    } else {
                        $btn_actions = '<a href="' . osc_static_page_url() . '" rel="tooltip" class="btn btn-light" title="' . __('View page') . '" target="_blank"><i class="material-icons">visibility</i><div class="ripple-container"></div></a>';
                        $btn_actions .= '<a href="' . osc_admin_base_url(true) . '?page=pages&action=edit&amp;id=' . $aRow['pk_i_id'] . '" rel="tooltip" class="btn btn-warning" title="' . __('Edit') . '"><i class="material-icons">edit</i><div class="ripple-container"></div></a>';

                        $btn_actions .= '<a id="listing-delete" data-delete-type="pages" data-listing-id="' . $aRow['pk_i_id'] . '" href="' . osc_admin_base_url(true) . '?page=pages&amp;action=delete&amp;id[]=' . $aRow['pk_i_id'] . '" rel="tooltip" class="btn btn-danger" title="' . __('Delete') . '"><i class="material-icons">delete</i><div class="ripple-container"></div></a>';

                        $row['bulkactions'] = '<div class="form-check">
                            <label class="form-check-label">
                                <input id="item-selected" class="form-check-input" type="checkbox" name="id[]" value="' . $aRow['pk_i_id'] . '"/>
                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>';

                        $row['internal_name'] = $aRow['s_internal_name'];
                        $row['title'] = $content['s_title'];
                        $row['order'] = '<span class="page-position">' . $aRow['i_order'] . '</span><div class="btn-group btn-group-sm">
                                          <button type="button" class="btn btn-sm btn-round btn-info" onclick="order_down(' . $aRow['pk_i_id'] . ');" title="' . __('Down') . '"> <i class="material-icons">remove</i> <div class="ripple-container"></div></button>
                                          <button type="button" class="btn btn-round btn-info" onclick="order_up(' . $aRow['pk_i_id'] . ');" title="' . __('Up') . '"> <i class="material-icons">add</i> <div class="ripple-container"></div></button>
                                        </div>';
                        $row['actions'] = $btn_actions;
                    }

                    $row = osc_apply_filter('pages_processing_row', $row, $aRow);

                    $this->addRow($row);
                    $this->rawRows[] = $aRow;
                }

            }
        }
        
    }

?>
