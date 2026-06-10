<?php
function kirimPesanSistem($host, $id_laporan, $pesan)
{
    $room_q = mysqli_query(
        $host,
        "SELECT id_room
         FROM chat_room
         WHERE id_laporan = '$id_laporan'"
    );
    if (mysqli_num_rows($room_q) == 0) {
        mysqli_query(
            $host,
            "INSERT INTO chat_room
            (id_laporan, created_at)
            VALUES
            ('$id_laporan', NOW())"
        );
        $id_room = mysqli_insert_id($host);
    } else {
        $room = mysqli_fetch_assoc($room_q);
        $id_room = $room['id_room'];
    }
    mysqli_query(
        $host,
        "INSERT INTO pesan
        (
            id_room,
            id_pengirim,
            isi_pesan,
            waktu_kirim
        )
        VALUES
        (
            '$id_room',
            NULL,
            '$pesan',
            NOW()
        )"
    );
}