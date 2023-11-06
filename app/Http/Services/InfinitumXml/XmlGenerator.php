<?php

namespace App\Http\Services\InfinitumXml;

use RuntimeException;
use XMLWriter;

final class XmlGenerator
{

    /**
     * @var XMLWriter
     */
    private XMLWriter|null $writer = null;

    public function __construct()
    {
        $this->writer = new XMLWriter();
        $this->writer->openMemory();
        $this->writer->startDocument();
    }

    /**
     * Добавить блок в DOM
     * @param string $name Наименование блока
     * @param string|null $content Содержание блока
     * @param array|null $attributes Атрибуты блока
     * @param string|null $comment Комментарий к блоку
     * @param bool|null $close Закрытие блока
     * @return $this
     */
    public function addElement(string $name, string|null $content = null, ?array $attributes = null, ?string $comment = null, ?bool $close = null): self
    {
        $close = $close ?? true;
        $this->writer->startElement($name);
        if ($comment) {
            $this->addComment($comment);
        }
        if ($attributes) {
            foreach ($attributes as $key => $value) {
                $this->addAttribute($key, $value);
            }
        }
        if (is_string($content)) {
            $this->setContent($content);
        }
        if ($close) {
            $this->closeElement();
        }
        return $this;
    }

    /**
     * Добавить комментарий
     * @param string $comment
     * @return $this
     */
    public function addComment(string $comment): self
    {
        $this->writer->startComment();
        $this->setContent($comment);
        $this->writer->endComment();
        return $this;
    }

    /**
     * Записать контент элемента
     * @param string $content
     * @return $this
     */
    public function setContent(string $content): self
    {
        $this->writer->text($content);
        return $this;
    }

    /**
     * Добавить атрибут
     * @param string|int $name Имя атрибута
     * @param string $value Значение атрибута
     * @return $this
     */
    public function addAttribute(string|int $name, string $value): self
    {
        $this->writer->startAttribute($name);
        $this->setContent($value);
        $this->writer->endAttribute();
        return $this;
    }

    /**
     * Закрыть блок
     * @return $this
     */
    public function closeElement(): self
    {
        $this->writer->endElement();
        return $this;
    }

    /**
     * Сохранить в файл
     * @param string $file Путь к файлу
     * @return string|null
     */
    public function saveToFile(string $file): ?string
    {
        $dir = dirname($file);
        if (!is_dir($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }
        if (file_put_contents($file, $this->getDomDocument())) {
            return $file;
        }
        return null;
    }

    /**
     * Получить XML в виде строки
     * @return string DOM-XML
     */
    public function getDomDocument(): string
    {
        $dom = $this->writer->outputMemory();
        $this->writer->endDocument();
        return $dom;
    }

}
