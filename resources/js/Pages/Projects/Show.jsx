import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Show({ project }) {
  const kw = useForm({
    project_id: project.id,
    phrase: '',
    locale: 'en',
    intent: '',
    volume: '',
    difficulty: '',
  });

  const brief = useForm({
    project_id: project.id,
    title: `SEO brief for ${project.name}`,
  });

  return (
    <AuthenticatedLayout>
      <Head title={project.name} />

      <div className="max-w-5xl mx-auto p-6 space-y-6">
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-xl font-semibold">{project.name}</h1>
            {project.description && <p className="text-gray-600">{project.description}</p>}
          </div>
          <Link className="underline" href={route('projects.index')}>Back</Link>
        </div>

        <div className="bg-white rounded-xl shadow p-6">
          <h2 className="text-lg font-semibold mb-4">Keywords</h2>
          <form onSubmit={(e) => { e.preventDefault(); kw.post(route('keywords.store')); }} className="grid grid-cols-1 md:grid-cols-6 gap-3">
            <input className="rounded border-gray-300 md:col-span-3" placeholder="keyword phrase" value={kw.data.phrase} onChange={(e) => kw.setData('phrase', e.target.value)} />
            <input className="rounded border-gray-300" placeholder="locale" value={kw.data.locale} onChange={(e) => kw.setData('locale', e.target.value)} />
            <input className="rounded border-gray-300" placeholder="intent" value={kw.data.intent} onChange={(e) => kw.setData('intent', e.target.value)} />
            <button className="rounded bg-black text-white px-4 py-2 disabled:opacity-50" disabled={kw.processing}>Add</button>
          </form>
          {kw.errors.phrase && <div className="text-sm text-red-600 mt-2">{kw.errors.phrase}</div>}

          <div className="mt-4 space-y-2">
            {project.keywords.map((k) => (
              <div key={k.id} className="border rounded p-2 text-sm">{k.phrase} <span className="text-gray-500">({k.locale})</span></div>
            ))}
            {project.keywords.length === 0 && <div className="text-gray-600">No keywords yet.</div>}
          </div>
        </div>

        <div className="bg-white rounded-xl shadow p-6">
          <h2 className="text-lg font-semibold mb-4">Briefs</h2>

          <form onSubmit={(e) => { e.preventDefault(); brief.post(route('briefs.store')); }} className="flex gap-3">
            <input className="flex-1 rounded border-gray-300" value={brief.data.title} onChange={(e) => brief.setData('title', e.target.value)} />
            <button className="rounded bg-black text-white px-4 py-2 disabled:opacity-50" disabled={brief.processing}>
              Generate
            </button>
          </form>

          <div className="mt-4 space-y-2">
            {project.briefs.map((b) => (
              <div key={b.id} className="flex items-center justify-between border rounded p-3">
                <div>
                  <div className="font-medium">{b.title}</div>
                  <div className="text-sm text-gray-600">{b.status} · {b.progress}%</div>
                </div>
                <Link className="underline" href={route('briefs.show', b.id)}>Open</Link>
              </div>
            ))}
            {project.briefs.length === 0 && <div className="text-gray-600">No briefs yet.</div>}
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  );
}
