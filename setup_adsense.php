<?php
$pdo = new PDO('sqlite:database/database.sqlite');

// A tabela adsense já existe, vamos apenas inserir dados de exemplo
try {
    // Limpar dados existentes se houver
    $pdo->exec("DELETE FROM adsense");
    
    // Inserir alguns anúncios de exemplo usando a estrutura existente
    $stmt = $pdo->prepare("INSERT INTO adsense (usuario_id, titulo, descricao, codigo_anuncio, tipo, posicao, largura, altura, ativo, clicks, impressoes, receita, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, datetime('now'), datetime('now'))");
    
    $anuncios = [
        [1, 'Banner Header Principal', 'Banner principal no cabeçalho do site', '<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script><ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-1234567890" data-ad-slot="1234567890" data-ad-format="auto"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script>', 'banner', 'header', 728, 90, 1, 156, 5420, 12.45],
        [1, 'Sidebar Direita', 'Anúncio na barra lateral direita', '<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script><ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-1234567890" data-ad-slot="9876543210" data-ad-format="auto"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script>', 'display', 'sidebar', 300, 250, 1, 89, 3210, 8.75],
        [2, 'Footer Banner', 'Banner no rodapé da página', '<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script><ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-1234567890" data-ad-slot="5555555555" data-ad-format="auto"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script>', 'banner', 'footer', 728, 90, 0, 45, 1890, 3.20],
        [2, 'Artigo Inline', 'Anúncio dentro do conteúdo dos artigos', '<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script><ins class="adsbygoogle" style="display:inline-block;width:336px;height:280px" data-ad-client="ca-pub-1234567890" data-ad-slot="7777777777"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script>', 'in-article', 'content', 336, 280, 1, 234, 6780, 18.90]
    ];

    foreach ($anuncios as $anuncio) {
        $stmt->execute($anuncio);
    }

    echo "Tabela AdSense populada com sucesso!\n";
    echo "Anúncios criados: Banner Header, Sidebar, Footer, Artigo Inline\n";
    echo "Status: 3 ativos, 1 inativo\n";
    
    $count = $pdo->query("SELECT COUNT(*) FROM adsense")->fetchColumn();
    echo "Total de anúncios: " . $count . "\n";
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
?>
