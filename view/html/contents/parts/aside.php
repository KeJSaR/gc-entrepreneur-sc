<?php

defined('SC_SAFETY_CONST') or die;


function build_class_tree ($parent_id, $m_data)
{
    global $current_section;
    $html = '';

    if (isset($m_data['parents'][$parent_id])) {
        $html .= '<ul>' . BR;
        foreach ($m_data['parents'][$parent_id] as $item_id) {
            // <li><p><i class="fa fa-square-o"></i> Бельё</p></li>
            $html .= '<li>'. BR . '<p data-class-id="' . $item_id . '"';

            if ($item_id == $current_section) {
                $html .= ' class="active"';
            }

            $html .= '><i class="fa ';

            if ($item_id == $current_section) {
                $html .= 'fa-check-square-o';
            } else {
                $html .= 'fa-square-o';
            }

            $html .= '"></i> ' . $m_data['items'][$item_id]['class_alias'] . '</p>' . BR;
            $html .= build_class_tree($item_id, $m_data);
            $html .= '</li>' . BR;
        }
        $html .= '</ul>' . BR;
    }

    return $html;
}

$aside_data = build_class_array();
$aside_html = build_class_tree(0, $aside_data);

?>

<?php

if (
    $current_page == 'goods'  ||
    $current_page == 'debit'  ||
    $current_page == 'credit' ||
    $current_page == 'move'
) :

?>
                    <div id="aside-header">
                        <span><?php echo L_CLASSES; ?></span>
                    </div>
<?php endif; ?>
                    <div id="aside-content">

<?php echo $aside_html; ?>

                    </div>
