<?php

namespace Src\Model;

use PDO;
use Exception;

class Ip extends Database
{
    private static $id_ip;
    private static $page;

    public static function create(array $data)
    {
        $pdo = self::getConnection();
        $data['ip'] = $_SERVER['REMOTE_ADDR'];

        try {
            $pdo->beginTransaction();

            if (self::verifyIp($pdo, $data)) {
                $pdo->commit();
                return true;
            }

            $pdo->rollBack();
            error_log("Erro: Falha ao verificar o IP.");
            return false;
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log("Erro em create(): " . $e->getMessage());
            return false;
        }
    }

    public static function updatePolicy(array $data)
    {
        $pdo = self::getConnection();
        $data['ip'] = $_SERVER['REMOTE_ADDR'];

        try {
            $pdo->beginTransaction();

            if (self::verifyIp($pdo, $data)) {
                if (self::alterarPolitica($pdo)) {
                    $pdo->commit();
                    return true;
                }
            }

            $pdo->rollBack();
            error_log("Erro: Falha ao atualizar política.");
            return false;
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log("Erro em updatePolicy(): " . $e->getMessage());
            return false;
        }
    }

    private static function verifyIp(PDO $pdo, array $data)
    {
        try {
            $sql = "SELECT id_ip FROM cad_ip WHERE ip = :ip AND DATE(data_primeiro_acesso) = :data";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":ip", $data['ip'], PDO::PARAM_STR);
            $today = date('Y-m-d');
            $stmt->bindParam(":data", $today, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                if (self::cadIp($pdo, $data)) {
                    return self::cadNavegacao($pdo);
                }
                error_log("Erro: Falha ao cadastrar IP.");
                return false;
            }

            $info = $stmt->fetchObject();
            self::$id_ip = $info->id_ip;
            self::$page = $data['page'] ?? '';

            return self::cadNavegacao($pdo);
        } catch (Exception $e) {
            error_log("Erro em verifyIp(): " . $e->getMessage());
            return false;
        }
    }

    private static function cadIp(PDO $pdo, array $data)
    {
        try {
            $data['ip'] = $_SERVER['REMOTE_ADDR'];

            if (filter_var($data['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                $data['ip'] = self::expandIpv6($data['ip']);
            }

            $sql = "INSERT INTO cad_ip (ip, localizacao, data_primeiro_acesso) VALUES (:ip, :localizacao, NOW())";
            $stmt = $pdo->prepare($sql);

            $localizacao = $data['loc'] ?? null;

            $stmt->bindParam(":ip", $data['ip'], PDO::PARAM_STR);
            $stmt->bindParam(":localizacao", $localizacao, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                self::$id_ip = $pdo->lastInsertId();
                self::$page = $data['page'] ?? '';
                return true;
            } else {
                error_log("Erro: Nenhuma linha foi inserida em cad_ip.");
                return false;
            }
        } catch (Exception $e) {
            error_log("Erro em cadIp(): " . $e->getMessage());
            return false;
        }
    }

    private static function expandIpv6($ipv6)
    {
        if (filter_var($ipv6, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $expanded = inet_ntop(inet_pton($ipv6)); // Expande para formato completo
            return $expanded;
        }

        return $ipv6; 
    }

    private static function cadNavegacao(PDO $pdo)
    {
        try {
            $sql = "INSERT INTO rel_ip_pages (id_ip, page) VALUES (:id_ip, :page)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":id_ip", self::$id_ip, PDO::PARAM_INT);
            $stmt->bindParam(":page", self::$page, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                error_log("Erro: Nenhuma linha foi inserida em rel_ip_pages.");
                return false;
            }
        } catch (Exception $e) {
            error_log("Erro em cadNavegacao(): " . $e->getMessage());
            return false;
        }
    }
    private static function alterarPolitica(PDO $pdo)
    {
        try {
            $sql = "SELECT id_ip FROM cad_ip WHERE ip = :ip";
            $stmt = $pdo->prepare($sql);
            $ip = $_SERVER['REMOTE_ADDR'];
            $stmt->bindParam(":ip", $ip, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                error_log("Erro: IP não encontrado em alterarPolitica().");
                return false;
            }

            $id_ip = $result['id_ip'];

            $sql = "UPDATE cad_ip SET politica_de_privacidade = 'Aceitou' WHERE id_ip = :id_ip";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":id_ip", $id_ip, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                error_log("Erro: Nenhuma linha foi atualizada em alterarPolitica().");
                return false;
            }
        } catch (Exception $e) {
            error_log("Erro em alterarPolitica(): " . $e->getMessage());
            return false;
        }
    }
}
