<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | DataTables</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="./plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="./plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="./plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="./plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="./dist/css/adminlte.min.css">
  <!-- Scoped file list theme -->
  <link rel="stylesheet" href="./filelist.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <!-- Ajoute ce bouton juste avant <div class="card"> -->
<button onclick="window.location.href='affichageacceuil.php'" class="btn btn-secondary" style="margin-bottom:15px;">
  <i class="fas fa-arrow-left"></i> Retour
</button>
<div class="card filelist">
                <div class="card-header">
                    <h3 class="card-title"><b>Liste des livres</b> </h3>
                </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div style="margin-bottom: 15px; display: flex; gap: 10px;">
                  <button onclick="window.location.href='formulaire_gestionnaire.php'" class="btn btn-success">
                    <i class="fas fa-plus"></i> Ajoutez
                  </button>
                  <button onclick="window.open('aperçu.php', '_blank')" class="btn btn-info">
                    <i class="fas fa-eye"></i> Aperçu
                  </button>
                </div>
                <table id="example1" class="table table-bordered table-striped filelist-table">
                  <thead>
                    <tr>
                      <th>Titre</th>
                      <th>Auteur</th>
                      <th>Année de publication</th>
                      <th>Genre</th>
                      <th>Couverture</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
<?php
include 'include/bd.php';
$sql = "SELECT * FROM livre";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['Titre']) . "</td>";
    echo "<td>" . htmlspecialchars($row['Auteur']) . "</td>";
    echo "<td>" . htmlspecialchars($row['Annee']) . "</td>";
    echo "<td>" . (isset($row['Genre']) ? htmlspecialchars($row['Genre']) : '') . "</td>";
    echo "<td>";
    if (!empty($row['Couverture'])) {
        echo '<img src="data:image/jpeg;base64,' . $row['Couverture'] . '" alt="Couverture" style="max-width:60px;max-height:80px;border-radius:4px;">';
    }
    echo "</td>";
    echo '<td>
        <a href="modification.php?id=' . $row['ID'] . '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Modifier</a>
        <a href="suppression.php?id=' . $row['ID'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Voulez-vous vraiment supprimer ce livre ?\')"><i class="fas fa-trash"></i> Supprimer</a>
    </td>';
    echo "</tr>";
}
$conn->close();
?>
</tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 3.2.0
    </div>
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">BELHADESIGN</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="./plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="./plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="./plugins/datatables/jquery.dataTables.min.js"></script>
<script src="./plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="./plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="./plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="./plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="./plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="./plugins/jszip/jszip.min.js"></script>
<script src="./plugins/pdfmake/pdfmake.min.js"></script>
<script src="./plugins/pdfmake/vfs_fonts.js"></script>
<script src="./plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="./plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="./plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="./dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="./dist/js/demo.js"></script>
<!-- Page specific script -->
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>

</body>
</html>
