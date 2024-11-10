<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RPPH Report</title>
    <style>
      table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
      }
      table,
      th,
      td {
        border: 1px solid black;
      }
      th,
      td {
        padding: 8px;
        text-align: left;
      }
      th {
        background-color: #f2f2f2;
      }
      .section-title {
        background-color: #f2f2f2;
        font-weight: bold;
      }
    /* Center the table horizontally */
    .centered-table {
        margin: 0 auto;
        border-collapse: collapse; /* Remove table border lines */
    }

    /* Remove all borders from the table and cells */
    .centered-table, .centered-table td {
        border: none;
        padding: 5px; /* Optional: Add some padding to cells */
        padding-bottom: 10px
        margin-top: 5px
    }

    /* Align text to the left in label and value cells */
    .centered-table .label, .centered-table .value {
        text-align: left;
    }

    /* Optional: Style for the section title */
    .section-title {
        font-weight: bold;
        font-size: 1.2em; /* Optional: Adjust font size */
        padding: 5px; /* Optional: Adjust padding */
    }

    /* Optional: Align text in the value column to the left */
    .value {
        text-align: left;
    }



    </style>
  </head>
  <body>
    <h2>RENCANA PELAKSANAAN PEMBELAJARAN HARIAN</h2>
    <table class="centered-table">
        <tr>

        </tr>
        <tr>
            <td colspan="3" class="label">Hari atau Tanggal</td>
            <td colspan="3" class="value">: {{$rpph->hari_atau_tanggal}}</td>
        </tr>
        <tr>
            <td colspan="3" class="label">Semester atau Bulan/Minggu</td>
            <td colspan="3" class="value">: {{$rpph->smt_atau_bln_minggu}}</td>
        </tr>
        <tr>
            <td colspan="3" class="label">Topik</td>
            <td colspan="3" class="value">: {{$rpph->topik}}</td>
        </tr>
        <tr>
            <td colspan="3" class="label">Elemen atau Sub Tema</td>
            <td colspan="3" class="value">: {{$rpph->elemen_atau_sub_tema}}</td>
        </tr>
        <tr>
            <td colspan="3" class="label">Kelompok atau Usia</td>
            <td colspan="3" class="value">: {{$rpph->kelompok_atau_usia}}</td>
        </tr>
    </table>


    <table>
      <tr>
        <td class="section-title">A. Tujuan Kegiatan</td>
      </tr>
      <tr>
        <td>{!! nl2br(e($rpph->tujuan_kegiatan)) !!}</td>
      </tr>
    </table>
    <table>
      <tr>
        <td class="section-title">B. Capaian Pembelajaran</td>
      </tr>
      <tr>
        <td>{!! nl2br(e($rpph->capaian_pembelajaran)) !!}</td>
      </tr>
    </table>
    <table>
      <tr>
        <td class="section-title">C. Alat dan Bahan</td>
      </tr>
      <tr>
        <td>{!! nl2br(e($rpph->alat_dan_bahan)) !!}</td>
      </tr>
    </table>
    <table>
        <tr>
          <td class="section-title">D. Kegiatan</td>
        </tr>
        <tr>
          <td>{!! nl2br(e($rpph->kegiatan)) !!}</td>
        </tr>
      </table>
  </body>
</html>
