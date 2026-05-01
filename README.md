# 📚 Bibliotheca Classica – Buscador de Livros com Laravel

<div align="center">

[![GitHub stars](https://img.shields.io/badge/status-concluído-brightgreen)]()
[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php)](https://php.net)

**Aplicação web que integra as APIs do Google Books e Open Library para pesquisa e visualização de livros.**

</div>

## 📖 Sobre o projeto

Bibliotheca Classica foi desenvolvido como **teste técnico para uma vaga de estágio** (infelizmente não fui aprovado). É uma aplicação Laravel que consome duas APIs públicas de livros:

- **Google Books API** – busca por termos, assuntos, visualização de capas e metadados.
- **Open Library API** – fonte complementar para pesquisa, com foco em livros de domínio público e acervo aberto.

O usuário pode pesquisar por título, autor ou assunto, visualizar detalhes (sinopse, autor, editora, número de páginas) e navegar por coleções temáticas.

## ✨ Funcionalidades

- 🔍 **Busca combinada** – resultados vindos do Google Books e Open Library na mesma página.
- 📖 **Detalhes do livro** – informações como título, autor(es), editora, data de publicação, número de páginas, descrição (quando disponível) e capa.
- 🏷️ **Navegação por assuntos** – seções com categorias pré-definidas (ex.: "Ficção Científica", "História", "Romance").
- 🎨 **Interface responsiva** – construída com Bootstrap 5 (via CDN) e Blade templates.
- ⚙️ **Configuração via .env** – chave da API Google Books opcional (modo sem chave funciona com limites reduzidos).

## 🛠️ Tecnologias

| Camada          | Tecnologia                                                                 |
|-----------------|----------------------------------------------------------------------------|
| Backend         | PHP 8.2+, Laravel 11                                                      |
| Frontend        | Blade templates, Bootstrap 5, CSS básico                                   |
| APIs externas   | Google Books API, Open Library API                                         |
| Cache           | Arquivo (driver `file`) – para otimizar requisições à API                  |
| Build tools     | Vite (para assets, se houver), npm                                        |
| Servidor local  | `php artisan serve`                                                        |

## 🚀 Como rodar localmente

### Pré‑requisitos
- PHP 8.2 ou superior (com extensões: `curl`, `json`, `mbstring`, `xml`, `sqlite3`)
- Composer
- Node.js e npm (opcional, apenas para compilar assets)
- SQLite (ou outro banco – o projeto usa SQLite por padrão)

## 📁 Project Structure

```
bibliotheca-classica/
├── app/
│   └── Http/
│       ├── Controllers/
│       │   └── BookController.php          # Controlador principal (busca, detalhes, assuntos)
│       └── Services/
│           ├── GoogleBooksService.php      # Wrapper para Google Books API
│           └── OpenLibraryService.php      # Wrapper para Open Library API
├── config/
│   └── services.php                        # Configuração da chave da API (lê do .env)
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php              # Layout base (header, nav, footer)
│       ├── home.blade.php                 # Página inicial com coleções em destaque
│       └── books/
│           ├── search.blade.php           # Resultados da busca (duas fontes)
│           ├── show.blade.php             # Detalhes de um livro específico
│           ├── subject.blade.php          # Página de navegação por assunto
│           └── partials/
│               ├── list-item.blade.php    # Item de resultado do Google Books
│               └── ol-list-item.blade.php # Item de resultado do Open Library
└── routes/
    └── web.php                            # Rotas da aplicação
```