<?php
namespace App\Controllers;

use App\Models\Product;
use App\Models\Movement;
use Fpdf\Fpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PDO;

class ReportController extends BaseController {
    private Product $productModel; // Tipado de propiedad
    private Movement $movementModel; // Tipado de propiedad

    public function __construct(PDO $conn) {
        parent::__construct($conn);
        $this->productModel = new Product($conn);
        $this->movementModel = new Movement($conn);
    }

    public function stockReports(): void {
        $this->checkAuth();
        $this->checkRole('admin'); // Solo administradores pueden ver reportes

        $productos_bajo_stock = $this->productModel->getProductsBelowMinStock();
        $productos_agotados = $this->productModel->getOutOfStockProducts();
        $productos_ok = $this->productModel->getSufficientStockProducts();

        $title = "Reportes de Stock";
        $this->render('reports/stock_reports', [
            'title' => $title,
            'productos_bajo_stock' => $productos_bajo_stock,
            'productos_agotados' => $productos_agotados,
            'productos_ok' => $productos_ok
        ]);
    }

    public function graphicReports(): void {
        $this->checkAuth();
        $this->checkRole('admin'); // Solo administradores pueden ver reportes

        $data_for_pie_chart = $this->productModel->getProductsCountByCategory();
        $data_for_bar_chart = $this->movementModel->getMovementsCountByType();

        $title = "Reportes Gráficos";
        $this->render('reports/graphic_reports', [
            'title' => $title,
            'data_for_pie_chart' => $data_for_pie_chart,
            'data_for_bar_chart' => $data_for_bar_chart
        ]);
    }

    public function generatePdf(): void {
        $this->checkAuth();
        $this->checkRole('admin'); // Solo administradores pueden generar PDFs

        $products = $this->productModel->getAllProducts();
        $pdf = new Fpdf();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(0,10,'Reporte Completo de Inventario - Bodega H4',0,1,'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(25,7,'Codigo',1);
        $pdf->Cell(50,7,'Nombre',1);
        $pdf->Cell(40,7,'Categoria',1);
        $pdf->Cell(30,7,'Ubicacion',1);
        $pdf->Cell(25,7,'Cantidad',1);
        $pdf->Cell(20,7,'Minimo',1);
        $pdf->Ln();

        $pdf->SetFont('Arial','',10);
        foreach($products as $product) {
            $pdf->Cell(25,7,htmlspecialchars($product['codigo']),1);
            $pdf->Cell(50,7,htmlspecialchars($product['nombre']),1);
            $pdf->Cell(40,7,htmlspecialchars($product['categoria']),1);
            $pdf->Cell(30,7,htmlspecialchars($product['ubicacion']),1);
            $pdf->Cell(25,7,htmlspecialchars($product['cantidad']),1);
            $pdf->Cell(20,7,htmlspecialchars($product['stock_minimo']),1);
            $pdf->Ln();
        }

        $filename = 'reporte_inventario_' . date('YmdHis') . '.pdf';
        ob_end_clean();
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $pdf->Output('D', $filename);
        exit();
    }

    public function generateExcel(): void {
        $this->checkAuth();
        $this->checkRole('admin'); // Solo administradores pueden generar Excels

        $products_out_of_stock = $this->productModel->getOutOfStockProducts();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Productos Agotados');

        $sheet->setCellValue('A1', 'Código');
        $sheet->setCellValue('B1', 'Nombre');
        $sheet->setCellValue('C1', 'Descripción');
        $sheet->setCellValue('D1', 'Categoría');
        $sheet->setCellValue('E1', 'Ubicación');
        $sheet->setCellValue('F1', 'Stock Actual');

        $row = 2;
        foreach ($products_out_of_stock as $product) {
            $sheet->setCellValue('A' . $row, htmlspecialchars($product['codigo']));
            $sheet->setCellValue('B' . $row, htmlspecialchars($product['nombre']));
            $sheet->setCellValue('C' . $row, htmlspecialchars($product['descripcion']));
            $sheet->setCellValue('D' . $row, htmlspecialchars($product['categoria']));
            $sheet->setCellValue('E' . $row, htmlspecialchars($product['ubicacion']));
            $sheet->setCellValue('F' . $row, htmlspecialchars($product['cantidad']));
            $row++;
        }

        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'reporte_agotados_' . date('YmdHis') . '.xlsx';

        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $writer->save('php://output');
        exit();
    }
}
