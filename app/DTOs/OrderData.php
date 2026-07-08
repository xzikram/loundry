<?php

namespace App\DTOs;

class OrderData
{
    public function __construct(
        public string $invoiceNumber,
        public int $customerId,
        public int $outletId,
        public int $createdBy,
        public ?int $processedBy = null,
        public string $status = 'pending',
        public string $priority = 'regular',
        public float $subtotal,
        public float $discount = 0.00,
        public float $tax = 0.00,
        public float $total,
        public ?string $notes = null,
        public ?string $specialInstructions = null,
        public ?string $estimatedCompletion = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            invoiceNumber: $data['invoice_number'],
            customerId: (int) $data['customer_id'],
            outletId: (int) $data['outlet_id'],
            createdBy: (int) $data['created_by'],
            processedBy: isset($data['processed_by']) ? (int) $data['processed_by'] : null,
            status: $data['status'] ?? 'pending',
            priority: $data['priority'] ?? 'regular',
            subtotal: (float) $data['subtotal'],
            discount: (float) ($data['discount'] ?? 0.00),
            tax: (float) ($data['tax'] ?? 0.00),
            total: (float) $data['total'],
            notes: $data['notes'] ?? null,
            specialInstructions: $data['special_instructions'] ?? null,
            estimatedCompletion: $data['estimated_completion'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'invoice_number' => $this->invoiceNumber,
            'customer_id' => $this->customerId,
            'outlet_id' => $this->outletId,
            'created_by' => $this->createdBy,
            'processed_by' => $this->processedBy,
            'status' => $this->status,
            'priority' => $this->priority,
            'subtotal' => $this->subtotal,
            'discount' => $this->discount,
            'tax' => $this->tax,
            'total' => $this->total,
            'notes' => $this->notes,
            'special_instructions' => $this->specialInstructions,
            'estimated_completion' => $this->estimatedCompletion,
        ];
    }
}
