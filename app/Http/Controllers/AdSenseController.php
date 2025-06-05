<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdSenseRequest;
use App\Http\Requests\UpdateAdSenseRequest;
use App\Models\AdSense;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdSenseController extends Controller
{
    /**
     * Lista todos os anúncios AdSense
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = AdSense::query();

            // Filtro por posição
            if ($request->has('posicao') && $request->posicao) {
                $query->porPosicao($request->posicao);
            }

            // Filtro por status ativo
            if ($request->has('ativo')) {
                $ativo = filter_var($request->ativo, FILTER_VALIDATE_BOOLEAN);
                $query->where('ativo', $ativo);
            }

            // Ordenação
            $orderBy = $request->get('order_by', 'created_at');
            $orderDirection = $request->get('order_direction', 'desc');
            $query->orderBy($orderBy, $orderDirection);

            // Paginação
            $perPage = $request->get('per_page', 15);
            
            if ($request->has('paginate') && $request->paginate === 'false') {
                $adsense = $query->get();
                $total = $adsense->count();
                $paginacao = null;
            } else {
                $adsense = $query->paginate($perPage);
                $total = $adsense->total();
                $paginacao = [
                    'pagina_atual' => $adsense->currentPage(),
                    'por_pagina' => $adsense->perPage(),
                    'total_paginas' => $adsense->lastPage(),
                    'total_itens' => $adsense->total()
                ];
                $adsense = $adsense->items();
            }

            return response()->json([
                'sucesso' => true,
                'dados' => $adsense,
                'total' => $total,
                'mensagem' => 'Anúncios AdSense listados com sucesso',
                'paginacao' => $paginacao
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Erro ao listar anúncios: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Armazena um novo anúncio AdSense
     */
    public function store(StoreAdSenseRequest $request): JsonResponse
    {
        try {
            $adsense = AdSense::create($request->validated());

            return response()->json([
                'sucesso' => true,
                'dados' => $adsense,
                'mensagem' => 'Anúncio AdSense criado com sucesso'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Erro ao criar anúncio: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exibe um anúncio específico
     */
    public function show(AdSense $adsense): JsonResponse
    {
        try {
            return response()->json([
                'sucesso' => true,
                'dados' => $adsense,
                'mensagem' => 'Anúncio encontrado com sucesso'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Erro ao buscar anúncio: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Atualiza um anúncio existente
     */
    public function update(UpdateAdSenseRequest $request, AdSense $adsense): JsonResponse
    {
        try {
            $adsense->update($request->validated());

            return response()->json([
                'sucesso' => true,
                'dados' => $adsense->fresh(),
                'mensagem' => 'Anúncio AdSense atualizado com sucesso'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Erro ao atualizar anúncio: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove um anúncio
     */
    public function destroy(AdSense $adsense): JsonResponse
    {
        try {
            $adsense->delete();

            return response()->json([
                'sucesso' => true,
                'mensagem' => 'Anúncio AdSense excluído com sucesso'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Erro ao excluir anúncio: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Alterna o status ativo de um anúncio
     */
    public function toggleStatus(AdSense $adsense): JsonResponse
    {
        try {
            $adsense->update(['ativo' => !$adsense->ativo]);

            return response()->json([
                'sucesso' => true,
                'dados' => $adsense->fresh(),
                'mensagem' => 'Status do anúncio alterado com sucesso'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Erro ao alterar status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lista anúncios por posição
     */
    public function porPosicao(Request $request, string $posicao): JsonResponse
    {
        try {
            $request->merge(['posicao' => $posicao]);
            $request->validate([
                'posicao' => 'required|in:header,sidebar,footer,content'
            ]);

            $adsense = AdSense::porPosicao($posicao)
                ->when($request->has('ativo'), function ($query) use ($request) {
                    return $query->where('ativo', filter_var($request->ativo, FILTER_VALIDATE_BOOLEAN));
                })
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'sucesso' => true,
                'dados' => $adsense,
                'total' => $adsense->count(),
                'mensagem' => "Anúncios da posição '{$posicao}' listados com sucesso"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Erro ao buscar anúncios por posição: ' . $e->getMessage()
            ], 500);
        }
    }
}
