<?php
require_once('function.php');
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

function get_fwt_datas($title, $cust_name = "", $pf_name = "") {
    global $pdo;
    $search_kw = [];

    $sql = "SELECT h.fwt_m_no, h.class, 
                   h.candidate1_date, h.candidate1_start, h.candidate1_end, h.candidate2_date, h.candidate2_start, h.candidate2_end,
                   h.candidate3_date, h.candidate3_start, h.candidate3_end, h.status,
                   h.client, c.cust_name, h.p_office_no, pf.pf_name, pf.person_in_charge, e.employee_name
            FROM fwt_m_tr h
            LEFT JOIN customer c ON h.client = c.cust_code
            LEFT JOIN public_office pf ON h.p_office_no = pf.pf_code
            LEFT JOIN employee e ON h.client = e.employee_code 
            WHERE 1=1";
    switch ($title) {
        case 'adjust':
            $sql .= " AND h.status = '1'";
            break;
        case 'booking':
            $sql .= " AND h.status = '2'";
            break;
        case 'confirm':
            $sql .= " AND h.status >= '3'";
            break;
    }

    if (!empty($cust_name)) {
        $search_kw['cust_name'] = '%' . $cust_name . '%';
        $sql .= " AND c.cust_name LIKE :cust_name";
    }
    if (!empty($pf_name)) {
        $search_kw['pf_name'] = '%' . $pf_name . '%';
        $sql .= " AND pf.pf_name LIKE :pf_name";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute($search_kw);

    $datas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $datas;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['isReturn'])) {
        $cust_name = $_POST['cust_name'] ?? "";
        $pf_name = $_POST['pf_name'] ?? "";
        $title = $_POST['title'] ?? "";

        $fwt_datas = get_fwt_datas($title, $cust_name, $pf_name);

        // Mapping for class
        $class_map = [
            1 => '工場見学',
            2 => '立会検査',
            3 => '技術研修'
        ];

        // Mapping for status
        $status_map = [
            1 => '仮予約済',
            2 => '日程入力済',
            3 => '本予約済',
            4 => '関係部署確認済',
            5 => '営業管理確認済',
            6 => '完了',
            7 => '却下'
        ];

        foreach ($fwt_datas as $item) {
            $class_text = $class_map[$item['class']] ?? $item['class'];
            $status_text = $status_map[$item['status']] ?? $item['status'];

            echo '<tr>';
            // echo '<td>' . htmlspecialchars($item['fwt_m_no']) . '</td>';
            echo '<td>' . htmlspecialchars($item['employee_name']) . '</td>';
            echo '<td>' . htmlspecialchars($class_text) . '</td>';
            echo '<td>' . htmlspecialchars($item['pf_name']) . '</td>';
            echo '<td>' . htmlspecialchars($item['candidate1_date']) . '</td>';
            echo '<td>' . htmlspecialchars($item['candidate1_start']) . htmlspecialchars($item['candidate1_end']) . '</td>';
            echo '<td>' . htmlspecialchars($item['candidate2_date']) . '</td>';
            echo '<td>' . htmlspecialchars($item['candidate2_start']) . htmlspecialchars($item['candidate2_end']) . '</td>';
            echo '<td>' . htmlspecialchars($item['candidate3_date']) . '</td>';
            echo '<td>' . htmlspecialchars($item['candidate3_start']) . htmlspecialchars($item['candidate3_end']) . '</td>';
            echo '<td>' . htmlspecialchars($status_text) . '</td>';
            echo '<td style="text-align:center"><button type="submit" class="updateBtn" data-fwt_m_no="' . htmlspecialchars($item['fwt_m_no']) . '" name="process" value="update">詳細</button></td>';
            echo '<input type="hidden" class="fwt_m_no" name="fwt_m_no" value="' . htmlspecialchars($item['fwt_m_no']) . '">';
            echo '</tr>';
        }
        exit;
    }
}
?>
