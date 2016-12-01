<?php
namespace controllers {
	include('/utils.php');
	class Artist {

		public function add($musicianId){
			$request = \Slim\Slim::getInstance()->request();
			$topArtists = json_decode($request->getBody());

			foreach ($topArtists as $artist) {
				// 1. Verificar se já está cadastrado, se já estiver, trazer do banco
				// -------------------------------------------------------------------
				$sql = "SELECT
							*
						FROM
							artist
						WHERE 
							uri = :uri" ;

				$conn = getConn();
				$stmt_artist = $conn->prepare($sql);
				$stmt_artist->bindParam("uri", $artist->uri);
				$stmt_artist->execute();
				$fetchedArtist = $stmt_artist->fetchObject();

				if($fetchedArtist) {
					$artist->id = $fetchedArtist->id;		
				}
				else {

					// 2. Caso não esteja, cadastrar no banco e pegar id criada
					// ------------------------------------------------------------

					$sql = "INSERT INTO artist (
								name, 
								image, 
								uri ) 
							values (
								:name, 
								:image, 
								:uri ) ";
					$conn = getConn();
					$stmt_insert = $conn->prepare($sql);

					$stmt_insert->bindParam("name", $artist->name);
					$stmt_insert->bindParam("image", $artist->images[0]->url);
					$stmt_insert->bindParam("uri", $artist->uri);
					$stmt_insert->execute();
					$artist->id = $conn->lastInsertId();

				}

				// 3. Relacionar artista com usuário na tabela musician_artist
				// ------------------------------------------------------------
				$sql = "INSERT INTO musician_artist (
							id_musician, 
							id_artist ) 
						values (
							:id_musician, 
							:id_artist ) ";

				$conn = getConn();
				$stm_relac = $conn->prepare($sql);
				$stm_relac->bindParam("id_musician", $musicianId);
				$stm_relac->bindParam("id_artist", $artist->id);
				$stm_relac->execute();

			}


		}


	}

}