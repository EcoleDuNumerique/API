<?php 
class NoteRepository extends Repository {

    function getAll(){

        $query = "SELECT * FROM notes";
        $result = $this->connection->query( $query );
        $result = $result->fetchAll( PDO::FETCH_ASSOC );

        $notes = [];
        foreach( $result as $data ){
            $notes[] = new Note( $data );
        }

        return $notes;  

    }

    function save( Note $note ){
        if( empty( $note->id ) ){
            return $this->insert( $note );
        }
        else {
            return $this->update( $note );
        }
    }

    private function insert( Note $note ){

        $query = "INSERT INTO notes SET title=:title, content=:content";
        $prep = $this->connection->prepare( $query );
        $prep->execute( [
            "title" => $note->getTitle(),
            "content" => $note->getContent()
        ] );
        return $this->connection->lastInsertId();

    }

    private function update( Note $note ){

        $query = "UPDATE notes SET title=:title, content=:content WHERE id=:id";
        $prep = $this->connection->prepare( $query );
        $prep->execute( [
            "title" => $note->getTitle(),
            "content" => $note->getContent(),
            "id" => $note->getId()
        ] );
        return $prep->rowCount();

    }

}